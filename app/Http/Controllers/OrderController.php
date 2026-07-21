<?php

namespace App\Http\Controllers;

use App\Mail\OrderFailed;
use App\Mail\OrderSuccess;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\PaymentGateway;
use App\Models\Coupon;
use App\Models\StockReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use App\Services\CartPricingService;
use App\Services\ShiprocketService;


class OrderController extends Controller
{
    private const STOCK_RESERVATION_MINUTES = 30;

    public function __construct(
        private CartPricingService $pricing,
        private ShiprocketService $shiprocket,
    ) {
    }

    private function customer()
    {
        return Auth::guard('customer')->user();
    }

    // ── Checkout Page ─────────────────────────────────────────────────
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $coupon = session()->get('coupon');
        $totals = $this->calculateCartTotals($cart, $coupon);

        $customer = $this->customer();
        $paymentGateways = PaymentGateway::getEnabled();
        $cashfreeGateway = $paymentGateways->firstWhere('gateway_name', 'cashfree');

        return view('checkout', array_merge($totals, compact('cart', 'coupon', 'customer', 'paymentGateways', 'cashfreeGateway')));
    }

    // ── Shared: Validate & Check Stock ────────────────────────────────
    private function validateCheckoutRequest(Request $request)
    {
        $request->merge([
            'name' => trim((string) $request->input('name', '')),
            'phone' => trim((string) $request->input('phone', '')),
            'email' => trim((string) $request->input('email', '')),
            'address' => trim((string) $request->input('address', '')),
            'city' => trim((string) $request->input('city', '')),
            'state' => trim((string) $request->input('state', '')),
            'pincode' => trim((string) $request->input('pincode', '')),
            'notes' => trim((string) $request->input('notes', '')),
        ]);

        $request->validate([
            'name' => 'required|string|min:2|max:255',
            'phone' => ['required', 'string', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|min:10|max:500',
            'city' => 'required|string|min:2|max:100',
            'state' => 'required|string|max:100',
            'pincode' => ['required', 'string', 'regex:/^[1-9][0-9]{5}$/'],
            'notes' => 'nullable|string|max:500',
        ], [
            'phone.regex' => 'Please enter a valid phone number.',
            'pincode.regex' => 'Please enter a valid 6-digit PIN code.',
        ]);
    }

    private function checkStock(array $cart): ?array
    {
        foreach ($cart as $item) {
            if (!empty($item['variation_id'])) {
                $variation = ProductVariation::find($item['variation_id']);
                if ($variation && $variation->product->manage_stock) {
                    if ($variation->stock_quantity < $item['quantity']) {
                        return ['error' => "Sorry! '{$item['name']}' only has {$variation->stock_quantity} units in stock."];
                    }
                }
            }
            else {
                $product = Product::find($item['product_id']);
                if ($product && $product->manage_stock && $product->variations->count() === 0) {
                    if ($product->stock_quantity < $item['quantity']) {
                        return ['error' => "Sorry! '{$item['name']}' only has {$product->stock_quantity} units in stock."];
                    }
                }
            }
        }
        return null;
    }

    private function calculateCartTotals(array $cart, ?array $coupon = null): array
    {
        return $this->pricing->calculate($cart, $coupon);
    }

    private function enabledGateway(string $gatewayName): ?PaymentGateway
    {
        return PaymentGateway::where('gateway_name', $gatewayName)
            ->where('is_enabled', true)
            ->first();
    }

    private function cashfreeBaseUrl(PaymentGateway $gateway): string
    {
        return $gateway->environment === 'production'
            ? 'https://api.cashfree.com/pg'
            : 'https://sandbox.cashfree.com/pg';
    }

    private function razorpayCredentials(PaymentGateway $gateway): array
    {
        return [
            'key_id' => config('razorpay.key_id') ?: $gateway->api_key,
            'key_secret' => config('razorpay.key_secret') ?: $gateway->secret_key,
        ];
    }

    private function cashfreeCredentials(PaymentGateway $gateway): array
    {
        return [
            'app_id' => config('cashfree.app_id') ?: $gateway->api_key,
            'secret_key' => config('cashfree.secret_key') ?: $gateway->secret_key,
        ];
    }

    private function cashfreeRequest(PaymentGateway $gateway)
    {
        $credentials = $this->cashfreeCredentials($gateway);

        return Http::withOptions([
            'proxy' => '',
        ])->timeout(30)->connectTimeout(10)->withHeaders([
            'x-client-id' => $credentials['app_id'],
            'x-client-secret' => $credentials['secret_key'],
            'x-api-version' => '2023-08-01',
        ]);
    }

    private function storeCheckoutSession(Request $request)
    {
        session()->put('checkout_data', [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'notes' => $request->notes,
        ]);
    }

    private function syncCustomerAddress(array $checkoutData): void
    {
        $customer = $this->customer();

        $customer->update([
            'phone' => $checkoutData['phone'],
            'address' => $checkoutData['address'],
            'city' => $checkoutData['city'],
            'state' => $checkoutData['state'],
            'pincode' => $checkoutData['pincode'],
            'country' => $checkoutData['country'] ?? 'India',
        ]);
    }

    private function createOrderInDB(array $checkoutData, array $cart, string $gateway, array $paymentIds, ?string $reservationToken = null): string
    {
        $orderNumber = null;

        $this->syncCustomerAddress($checkoutData);
        $this->releaseExpiredStockReservations();

        DB::transaction(function () use ($checkoutData, $cart, $gateway, $paymentIds, $reservationToken, &$orderNumber) {
            $customer = $this->customer();
            $this->assertCurrentCartPrices($cart);
            $coupon = $this->validatedCouponForOrder($cart);
            $totals = $this->calculateCartTotals($cart, $coupon);

            if ($reservationToken) {
                $this->consumeStockReservation($cart, $reservationToken);
            } else {
                $this->reserveStock($cart);
            }

            $orderData = [
                'customer_id' => $customer->id,
                'order_number' => 'BB-' . strtoupper(uniqid()),
                'name' => $checkoutData['name'],
                'phone' => $checkoutData['phone'],
                'email' => $checkoutData['email'] ?? $customer->email,
                'address' => $checkoutData['address'],
                'city' => $checkoutData['city'],
                'state' => $checkoutData['state'],
                'pincode' => $checkoutData['pincode'],
                'notes' => $checkoutData['notes'],
                'total_amount' => $totals['total'],
                'shipping_amount' => $totals['shippingTotal'],
                'tax_amount' => $totals['taxAmount'],
                'coupon_id' => $coupon['id'] ?? null,
                'discount_amount' => $totals['discount'],
                'net_amount' => $totals['total'],
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'payment_gateway' => $gateway,
            ];

            // Merge gateway-specific IDs
            $orderData = array_merge($orderData, $paymentIds);

            $order = Order::create($orderData);

            foreach ($cart as $item) {
                $product = Product::find($item['product_id']);
                $gstRate = (float) ($product?->tax_rate ?? $product?->gst_rate ?? 0);
                $lineSubtotal = (float) $item['price'] * (int) $item['quantity'];
                $lineTax = $gstRate > 0 ? ($lineSubtotal * $gstRate / 100) : 0;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'variation_id' => $item['variation_id'] ?? null,
                    'product_name' => $item['name'],
                    'variation_name' => $item['variation'] ?? null,
                    'sku' => $item['sku'] ?? null,
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $lineSubtotal,
                    'shipping_charge' => $item['shipping_charge'] ?? 0,
                    'gst_rate' => $gstRate,
                    'tax_amount' => $lineTax,
                    'net_price' => $lineSubtotal,
                ]);
            }

            if ($coupon) {
                Coupon::where('id', $coupon['id'])->increment('used_count');
            }

            $orderNumber = $order->order_number;
            session()->forget(['cart', 'checkout_data', 'coupon', 'stock_reservation_token', 'stock_reservation_expires_at']);
        });

        // Push to Shiprocket
        try {
            $result = $this->shiprocket->createOrder(
                Order::where('order_number', $orderNumber)->with('items')->first()
            );
            if (!empty($result['order_id'])) {
                Order::where('order_number', $orderNumber)
                    ->update(['shiprocket_order_id' => $result['order_id']]);
            }
        } catch (\Exception $e) {
            Log::error('Shiprocket push failed: ' . $e->getMessage());
        }

        return $orderNumber ?? '';

    }

    private function createPendingOnlineOrder(array $checkoutData, array $cart, string $gateway, array $paymentIds, ?string $reservationToken = null): Order
    {
        $this->syncCustomerAddress($checkoutData);
        $this->releaseExpiredStockReservations();

        return DB::transaction(function () use ($checkoutData, $cart, $gateway, $paymentIds, $reservationToken) {
            $customer = $this->customer();
            $this->assertCurrentCartPrices($cart);
            $coupon = $this->validatedCouponForOrder($cart);
            $totals = $this->calculateCartTotals($cart, $coupon);

            if ($reservationToken) {
                $this->consumeStockReservation($cart, $reservationToken);
            } else {
                $this->reserveStock($cart);
            }

            $order = Order::create(array_merge([
                'customer_id' => $customer->id,
                'order_number' => 'BB-' . strtoupper(uniqid()),
                'name' => $checkoutData['name'],
                'phone' => $checkoutData['phone'],
                'email' => $checkoutData['email'] ?? $customer->email,
                'address' => $checkoutData['address'],
                'city' => $checkoutData['city'],
                'state' => $checkoutData['state'],
                'pincode' => $checkoutData['pincode'],
                'notes' => $checkoutData['notes'] ?? null,
                'total_amount' => $totals['total'],
                'shipping_amount' => $totals['shippingTotal'],
                'tax_amount' => $totals['taxAmount'],
                'coupon_id' => $coupon['id'] ?? null,
                'discount_amount' => $totals['discount'],
                'net_amount' => $totals['total'],
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_gateway' => $gateway,
            ], $paymentIds));

            $this->createOrderItems($order, $cart);

            session()->put('pending_order_number', $order->order_number);

            return $order;
        });
    }

    private function createOrderItems(Order $order, array $cart): void
    {
        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            $gstRate = (float) ($product?->tax_rate ?? $product?->gst_rate ?? 0);
            $lineSubtotal = (float) $item['price'] * (int) $item['quantity'];
            $lineTax = $gstRate > 0 ? ($lineSubtotal * $gstRate / 100) : 0;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'variation_id' => $item['variation_id'] ?? null,
                'product_name' => $item['name'],
                'variation_name' => $item['variation'] ?? null,
                'sku' => $item['sku'] ?? null,
                'unit_price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $lineSubtotal,
                'shipping_charge' => $item['shipping_charge'] ?? 0,
                'gst_rate' => $gstRate,
                'tax_amount' => $lineTax,
                'net_price' => $lineSubtotal,
            ]);
        }
    }

    private function completePaidOrder(Order $order, array $paymentIds = []): bool
    {
        $completed = false;

        DB::transaction(function () use ($order, $paymentIds, &$completed) {
            $lockedOrder = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            if ($lockedOrder->payment_status === 'paid') {
                return;
            }

            if (!empty($paymentIds['razorpay_payment_id']) && Order::where('razorpay_payment_id', $paymentIds['razorpay_payment_id'])->whereKeyNot($lockedOrder->id)->exists()) {
                throw new \RuntimeException('This Razorpay payment has already been processed for another order.');
            }

            if (!empty($paymentIds['cashfree_payment_id']) && Order::where('cashfree_payment_id', $paymentIds['cashfree_payment_id'])->whereKeyNot($lockedOrder->id)->exists()) {
                throw new \RuntimeException('This Cashfree payment has already been processed for another order.');
            }

            $lockedOrder->fill(array_merge([
                'status' => 'confirmed',
                'payment_status' => 'paid',
            ], array_filter($paymentIds, fn ($value) => filled($value))));
            $lockedOrder->save();

            if ($lockedOrder->coupon_id) {
                Coupon::where('id', $lockedOrder->coupon_id)->increment('used_count');
            }

            $completed = true;
        });

        if ($completed) {
            $freshOrder = $order->fresh(['items']);

            try {
                Mail::to($freshOrder->email)->send(new OrderSuccess($freshOrder));
            } catch (\Exception $e) {
                Log::error('Failed to send order success email: ' . $e->getMessage());
            }

            $this->pushPaidOrderToShiprocket($freshOrder);
        }

        return $completed;
    }

    private function failPendingOrder(Order $order, string $reason = 'Payment failed'): bool
    {
        $failed = false;

        DB::transaction(function () use ($order, &$failed) {
            $lockedOrder = Order::with('items')->whereKey($order->id)->lockForUpdate()->firstOrFail();

            if ($lockedOrder->payment_status !== 'pending') {
                return;
            }

            foreach ($lockedOrder->items as $item) {
                if ($item->variation_id) {
                    $variation = ProductVariation::with('product')->find($item->variation_id);
                    if ($variation && $variation->product?->manage_stock) {
                        $variation->increment('stock_quantity', $item->quantity);
                    }
                    continue;
                }

                $product = Product::withCount('variations')->find($item->product_id);
                if ($product && $product->manage_stock && $product->variations_count === 0) {
                    $product->increment('stock_quantity', $item->quantity);
                }
            }

            $lockedOrder->update([
                'status' => 'cancelled',
                'payment_status' => 'failed',
            ]);

            $failed = true;
        });

        if ($failed) {
            try {
                Mail::to($order->email)->send(new OrderFailed($order->fresh(), $reason));
            } catch (\Exception $e) {
                Log::error('Failed to send order failed email: ' . $e->getMessage());
            }
        }

        return $failed;
    }

    private function pushPaidOrderToShiprocket(Order $order): void
    {
        if ($order->shiprocket_order_id) {
            return;
        }

        try {
            $result = $this->shiprocket->createOrder($order->loadMissing('items'));
            if (!empty($result['order_id'])) {
                $order->update(['shiprocket_order_id' => $result['order_id']]);
            }
        } catch (\Exception $e) {
            Log::error('Shiprocket push failed: ' . $e->getMessage());
        }
    }

    private function assertCurrentCartPrices(array $cart): void
    {
        foreach ($cart as $item) {
            if (!empty($item['variation_id'])) {
                $currentPrice = ProductVariation::where('id', $item['variation_id'])
                    ->where('product_id', $item['product_id'])
                    ->value('price');
            } else {
                $currentPrice = Product::where('id', $item['product_id'])->value('base_price');
            }

            if ($currentPrice === null) {
                throw new \RuntimeException("Product '{$item['name']}' is no longer available. Please refresh your cart.");
            }

            if (abs((float) $item['price'] - (float) $currentPrice) > 0.01) {
                throw new \RuntimeException("Price for '{$item['name']}' has changed. Please refresh your cart.");
            }
        }
    }

    private function validatedCouponForOrder(array $cart): ?array
    {
        $sessionCoupon = session()->get('coupon');

        if (!$sessionCoupon || empty($sessionCoupon['code'])) {
            return null;
        }

        $coupon = Coupon::where('code', $sessionCoupon['code'])
            ->where('is_active', true)
            ->lockForUpdate()
            ->first();

        if (!$coupon) {
            throw new \RuntimeException('The selected coupon is no longer available.');
        }

        if ($coupon->expires_at && \Carbon\Carbon::parse($coupon->expires_at)->isPast()) {
            throw new \RuntimeException('The selected coupon has expired.');
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            throw new \RuntimeException('The selected coupon usage limit has been reached.');
        }

        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
        if ($subtotal < $coupon->min_order_amount) {
            throw new \RuntimeException('The selected coupon is no longer valid for this cart.');
        }

        return [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
        ];
    }

    private function reserveStock(array $cart): void
    {
        foreach ($cart as $item) {
            $quantity = (int) $item['quantity'];

            if (!empty($item['variation_id'])) {
                $variation = ProductVariation::with('product')
                    ->where('id', $item['variation_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$variation || ($variation->product->manage_stock && $variation->stock_quantity < $quantity)) {
                    throw new \RuntimeException("Sorry! '{$item['name']}' does not have enough stock.");
                }

                if ($variation->product->manage_stock) {
                    $variation->decrement('stock_quantity', $quantity);
                }

                continue;
            }

            $product = Product::withCount('variations')
                ->where('id', $item['product_id'])
                ->lockForUpdate()
                ->first();

            if (!$product || ($product->manage_stock && $product->variations_count === 0 && $product->stock_quantity < $quantity)) {
                throw new \RuntimeException("Sorry! '{$item['name']}' does not have enough stock.");
            }

            if ($product->manage_stock && $product->variations_count === 0) {
                $product->decrement('stock_quantity', $quantity);
            }
        }
    }

    private function createStockReservation(array $cart): ?string
    {
        $this->releaseExpiredStockReservations();

        return DB::transaction(function () use ($cart) {
            $this->releaseSessionStockReservations();
            $this->assertCurrentCartPrices($cart);

            $token = (string) Str::uuid();
            $expiresAt = now()->addMinutes(self::STOCK_RESERVATION_MINUTES);
            $reservedLines = 0;

            foreach ($cart as $item) {
                $quantity = (int) $item['quantity'];

                if (!empty($item['variation_id'])) {
                    $variation = ProductVariation::with('product')
                        ->where('id', $item['variation_id'])
                        ->where('product_id', $item['product_id'])
                        ->lockForUpdate()
                        ->first();

                    if (!$variation || ($variation->product->manage_stock && $variation->stock_quantity < $quantity)) {
                        throw new \RuntimeException("Sorry! '{$item['name']}' does not have enough stock.");
                    }

                    if ($variation->product->manage_stock) {
                        $variation->decrement('stock_quantity', $quantity);
                        $this->recordStockReservation($token, $item, $quantity, $expiresAt);
                        $reservedLines++;
                    }

                    continue;
                }

                $product = Product::withCount('variations')
                    ->where('id', $item['product_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$product || ($product->manage_stock && $product->variations_count === 0 && $product->stock_quantity < $quantity)) {
                    throw new \RuntimeException("Sorry! '{$item['name']}' does not have enough stock.");
                }

                if ($product->manage_stock && $product->variations_count === 0) {
                    $product->decrement('stock_quantity', $quantity);
                    $this->recordStockReservation($token, $item, $quantity, $expiresAt);
                    $reservedLines++;
                }
            }

            if ($reservedLines === 0) {
                session()->forget(['stock_reservation_token', 'stock_reservation_expires_at']);

                return null;
            }

            session()->put('stock_reservation_token', $token);
            session()->put('stock_reservation_expires_at', $expiresAt->toIso8601String());

            return $token;
        });
    }

    private function recordStockReservation(string $token, array $item, int $quantity, \DateTimeInterface $expiresAt): void
    {
        StockReservation::create([
            'customer_id' => $this->customer()?->id,
            'session_id' => session()->getId(),
            'token' => $token,
            'product_id' => $item['product_id'],
            'variation_id' => $item['variation_id'] ?? null,
            'quantity' => $quantity,
            'expires_at' => $expiresAt,
        ]);
    }

    private function consumeStockReservation(array $cart, string $token): void
    {
        $reservations = StockReservation::where('token', $token)
            ->where('status', 'active')
            ->lockForUpdate()
            ->get();

        if ($reservations->isEmpty()) {
            throw new \RuntimeException('Your stock reservation has expired. Please start checkout again.');
        }

        if ($reservations->contains(fn (StockReservation $reservation) => $reservation->expires_at->isPast())) {
            throw new \RuntimeException('Your stock reservation has expired. Please start checkout again.');
        }

        $expected = $this->reservableCartLines($cart);
        $reserved = $reservations
            ->mapWithKeys(fn (StockReservation $reservation) => [
                $this->stockReservationKey($reservation->product_id, $reservation->variation_id) => $reservation->quantity,
            ])
            ->all();

        ksort($expected);
        ksort($reserved);

        if ($expected !== $reserved) {
            throw new \RuntimeException('Your cart changed after stock was reserved. Please start checkout again.');
        }

        StockReservation::whereIn('id', $reservations->pluck('id'))
            ->update(['status' => 'consumed']);
    }

    private function reservableCartLines(array $cart): array
    {
        $lines = [];

        foreach ($cart as $item) {
            if (!empty($item['variation_id'])) {
                $variation = ProductVariation::with('product')
                    ->where('id', $item['variation_id'])
                    ->where('product_id', $item['product_id'])
                    ->first();

                if ($variation && $variation->product->manage_stock) {
                    $key = $this->stockReservationKey((int) $item['product_id'], (int) $item['variation_id']);
                    $lines[$key] = ($lines[$key] ?? 0) + (int) $item['quantity'];
                }

                continue;
            }

            $product = Product::withCount('variations')->find($item['product_id']);
            if ($product && $product->manage_stock && $product->variations_count === 0) {
                $key = $this->stockReservationKey((int) $item['product_id'], null);
                $lines[$key] = ($lines[$key] ?? 0) + (int) $item['quantity'];
            }
        }

        return $lines;
    }

    private function cartRequiresStockReservation(array $cart): bool
    {
        return !empty($this->reservableCartLines($cart));
    }

    private function stockReservationKey(int $productId, ?int $variationId): string
    {
        return $productId . ':' . ($variationId ?: 'product');
    }

    private function releaseSessionStockReservations(): void
    {
        $tokens = StockReservation::where('status', 'active')
            ->where(function ($query) {
                $query->where('session_id', session()->getId());

                if ($this->customer()) {
                    $query->orWhere('customer_id', $this->customer()->id);
                }
            })
            ->distinct()
            ->pluck('token');

        foreach ($tokens as $token) {
            $this->releaseStockReservation($token);
        }
    }

    private function releaseExpiredStockReservations(): int
    {
        $tokens = StockReservation::where('status', 'active')
            ->where('expires_at', '<=', now())
            ->distinct()
            ->pluck('token');

        foreach ($tokens as $token) {
            $this->releaseStockReservation($token);
        }

        return $tokens->count();
    }

    private function releaseStockReservation(string $token, string $status = 'released'): void
    {
        DB::transaction(function () use ($token, $status) {
            $reservations = StockReservation::where('token', $token)
                ->where('status', 'active')
                ->lockForUpdate()
                ->get();

            foreach ($reservations as $reservation) {
                if ($reservation->variation_id) {
                    ProductVariation::where('id', $reservation->variation_id)
                        ->increment('stock_quantity', $reservation->quantity);
                } else {
                    Product::where('id', $reservation->product_id)
                        ->increment('stock_quantity', $reservation->quantity);
                }
            }

            StockReservation::whereIn('id', $reservations->pluck('id'))
                ->update(['status' => $status]);
        });
    }

    private function paidStockFailureResponse(Request $request, string $gateway, array $paymentIds, \RuntimeException $e)
    {
        Log::critical('Paid order failed during post-payment order creation. Manual refund review required.', [
            'gateway' => $gateway,
            'payment_ids' => $paymentIds,
            'customer_id' => $this->customer()?->id,
            'ip' => $request->ip(),
            'reason' => $e->getMessage(),
            'cart' => collect(session()->get('cart', []))->map(fn ($item) => [
                'product_id' => $item['product_id'] ?? null,
                'variation_id' => $item['variation_id'] ?? null,
                'quantity' => $item['quantity'] ?? null,
                'name' => $item['name'] ?? null,
            ])->values()->all(),
        ]);

        if ($reservationToken = session()->get('stock_reservation_token')) {
            $this->releaseStockReservation($reservationToken);
            session()->forget(['stock_reservation_token', 'stock_reservation_expires_at']);
        }

        return response()->json([
            'error' => 'Payment was received, but the order could not be placed because stock or checkout details changed before confirmation. Please do not retry payment. Our team has been alerted for manual review/refund.',
            'requires_manual_refund' => true,
        ], 409);
    }

    // ── RAZORPAY: Step 1 — Create Order ───────────────────────────────
    public function createRazorpayOrder(Request $request)
    {
        $gateway = $this->enabledGateway('razorpay');
        if (!$gateway) {
            return response()->json(['error' => 'Razorpay is disabled. Please select an enabled payment method.'], 422);
        }

        $credentials = $this->razorpayCredentials($gateway);
        if (empty($credentials['key_id']) || empty($credentials['key_secret'])) {
            return response()->json(['error' => 'Razorpay credentials are not configured in .env.'], 500);
        }

        $this->validateCheckoutRequest($request);

        $cart = session()->get('cart', []);
        if (empty($cart))
            return response()->json(['error' => 'Your cart is empty.'], 422);

        $coupon = session()->get('coupon');
        $totals = $this->calculateCartTotals($cart, $coupon);
        $total = $totals['total'];

        try {
            $reservationToken = $this->createStockReservation($cart);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        $api = new Api($credentials['key_id'], $credentials['key_secret']);
        try {
            $razorpayOrder = $api->order->create([
                'receipt' => 'BB-' . strtoupper(uniqid()),
                'amount' => (int)round($total * 100),
                'currency' => 'INR',
            ]);
        } catch (\Throwable $e) {
            if ($reservationToken) {
                $this->releaseStockReservation($reservationToken);
            }
            session()->forget(['stock_reservation_token', 'stock_reservation_expires_at']);
            Log::error('Razorpay create order failed: ' . $e->getMessage());

            return response()->json(['error' => 'Failed to create Razorpay order. Please try again.'], 500);
        }

        $this->storeCheckoutSession($request);
        $customer = $this->customer();

        try {
            $pendingOrder = $this->createPendingOnlineOrder(
                session()->get('checkout_data'),
                $cart,
                'razorpay',
                ['razorpay_order_id' => $razorpayOrder->id],
                $reservationToken
            );
        } catch (\RuntimeException $e) {
            if ($reservationToken) {
                $this->releaseStockReservation($reservationToken);
            }
            session()->forget(['stock_reservation_token', 'stock_reservation_expires_at']);
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json([
            'success' => true,
            'order_number' => $pendingOrder->order_number,
            'razorpay_order_id' => $razorpayOrder->id,
            'amount' => (int)round($total * 100),
            'currency' => 'INR',
            'key_id' => $credentials['key_id'],
            'stock_reservation_expires_at' => session()->get('stock_reservation_expires_at'),
            'name' => $customer->name,
            'email' => $customer->email,
            'phone' => $customer->phone ?? $request->phone,
        ]);
    }

    // ── RAZORPAY: Step 2 — Verify & Save Order ────────────────────────
    public function paymentSuccess(Request $request)
    {
        $gateway = $this->enabledGateway('razorpay');
        if (!$gateway) {
            return response()->json(['error' => 'Razorpay is disabled. Please select an enabled payment method.'], 422);
        }

        $credentials = $this->razorpayCredentials($gateway);
        if (empty($credentials['key_id']) || empty($credentials['key_secret'])) {
            return response()->json(['error' => 'Razorpay credentials are not configured in .env.'], 500);
        }

        $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $api = new Api($credentials['key_id'], $credentials['key_secret']);
        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);
        }
        catch (SignatureVerificationError $e) {
            return response()->json(['error' => 'Payment verification failed. Please try again.'], 400);
        }

        $order = Order::where('razorpay_order_id', $request->razorpay_order_id)->first();
        if (!$order) {
            return response()->json(['error' => 'Order not found for this payment. Please contact support.'], 404);
        }

        try {
            $paymentIds = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
            ];

            $this->completePaidOrder($order, $paymentIds);
        } catch (\RuntimeException $e) {
            return $this->paidStockFailureResponse($request, 'razorpay', $paymentIds ?? [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
            ], $e);
        }

        session()->forget(['cart', 'checkout_data', 'coupon', 'stock_reservation_token', 'stock_reservation_expires_at', 'pending_order_number']);

        return response()->json(['success' => true, 'redirect_url' => route('order.success', $order->order_number)]);
    }

    // ── CASHFREE: Step 1 — Create Order ───────────────────────────────
    public function createCashfreeOrder(Request $request)
    {
        $gateway = $this->enabledGateway('cashfree');
        if (!$gateway) {
            return response()->json(['error' => 'Cashfree is disabled. Please select an enabled payment method.'], 422);
        }

        $credentials = $this->cashfreeCredentials($gateway);
        if (empty($credentials['app_id']) || empty($credentials['secret_key'])) {
            return response()->json(['error' => 'Cashfree credentials are not configured in .env.'], 500);
        }

        $this->validateCheckoutRequest($request);

        $cart = session()->get('cart', []);
        if (empty($cart))
            return response()->json(['error' => 'Your cart is empty.'], 422);

        $coupon = session()->get('coupon');
        $totals = $this->calculateCartTotals($cart, $coupon);
        $total = $totals['total'];
        $customer = $this->customer();
        $orderId = 'BB-' . strtoupper(uniqid());

        try {
            $reservationToken = $this->createStockReservation($cart);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        $returnUrl = $request->getSchemeAndHttpHost() . route('order.cashfree.verify', [], false) . '?order_id={order_id}';

        $response = $this->cashfreeRequest($gateway)->withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->cashfreeBaseUrl($gateway) . '/orders', [
            'order_id' => $orderId,
            'order_amount' => round($total, 2),
            'order_currency' => 'INR',
            'customer_details' => [
                'customer_id' => (string)$customer->id,
                'customer_name' => $request->name,
                'customer_email' => $request->email ?? $customer->email,
                'customer_phone' => $request->phone,
            ],
            'order_meta' => [
                'return_url' => $returnUrl,
            ],
        ]);

        if (!$response->successful()) {
            if ($reservationToken) {
                $this->releaseStockReservation($reservationToken);
            }
            session()->forget(['stock_reservation_token', 'stock_reservation_expires_at']);
            Log::error('Cashfree create order failed: ' . $response->body());
            $message = 'Failed to create Cashfree order. Please check Cashfree credentials and environment.';
            if ($response->json('message')) {
                $message = $response->json('message');
            }
            return response()->json(['error' => $message], 500);
        }

        $this->storeCheckoutSession($request);

        $data = $response->json();

        if (empty($data['payment_session_id']) || !is_string($data['payment_session_id'])) {
            if ($reservationToken) {
                $this->releaseStockReservation($reservationToken);
            }
            session()->forget(['stock_reservation_token', 'stock_reservation_expires_at']);
            Log::error('Cashfree create order missing payment_session_id: ' . json_encode($data));
            return response()->json(['error' => 'Cashfree did not return a valid payment_session_id. Please check Cashfree configuration and logs.'], 500);
        }

        // ── SECURITY: Bind the Cashfree order_id to this session so that
        //   verifyCashfreePayment() can confirm the GET parameter was not
        //   tampered with (prevents cross-user order_id hijacking).
        session()->put('cashfree_pending_order_id', $orderId);

        try {
            $pendingOrder = $this->createPendingOnlineOrder(
                session()->get('checkout_data'),
                $cart,
                'cashfree',
                ['cashfree_order_id' => $orderId],
                $reservationToken
            );
        } catch (\RuntimeException $e) {
            if ($reservationToken) {
                $this->releaseStockReservation($reservationToken);
            }
            session()->forget(['stock_reservation_token', 'stock_reservation_expires_at']);
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json([
            'success' => true,
            'order_number' => $pendingOrder->order_number,
            'payment_session_id' => $data['payment_session_id'],
            'cashfree_order_id' => $data['order_id'],
            'stock_reservation_expires_at' => session()->get('stock_reservation_expires_at'),
        ]);
    }

    // ── CASHFREE: Step 2 — Verify & Save Order ────────────────────────
    public function verifyCashfreePayment(Request $request)
    {
        $gateway = $this->enabledGateway('cashfree');
        if (!$gateway) {
            return response()->json(['error' => 'Cashfree is disabled. Please select an enabled payment method.'], 422);
        }

        $credentials = $this->cashfreeCredentials($gateway);
        if (empty($credentials['app_id']) || empty($credentials['secret_key'])) {
            return response()->json(['error' => 'Cashfree credentials are not configured in .env.'], 500);
        }

        $orderId          = $request->input('order_id');
        $sessionOrderId   = session()->get('cashfree_pending_order_id');

        // ── SECURITY: Reject if the order_id in the GET parameter does not
        //   match the one this session generated in Step 1. This prevents an
        //   attacker from crafting a URL with a foreign Cashfree order_id and
        //   hijacking another customer's payment to create a free order.
        if (!$orderId || !$sessionOrderId || !hash_equals((string) $sessionOrderId, (string) $orderId)) {
            Log::warning('Cashfree verify: order_id mismatch or missing session binding.', [
                'received'  => $orderId,
                'expected'  => $sessionOrderId,
                'customer'  => $this->customer()?->id,
                'ip'        => $request->ip(),
            ]);
            return response()->json(['error' => 'Invalid or expired payment session. Please start checkout again.'], 422);
        }

        // Clear the pending order ID so this session cannot be replayed.
        session()->forget('cashfree_pending_order_id');

        // Verify with Cashfree server-side
        $response = $this->cashfreeRequest($gateway)
            ->get($this->cashfreeBaseUrl($gateway) . '/orders/' . $orderId);

        if (!$response->successful()) {
            Log::error('Cashfree verify order failed: ' . $response->body());
            return response()->json(['error' => 'Could not verify payment with Cashfree. Please check the Cashfree response logs.'], 500);
        }

        $orderData = $response->json();

        if (($orderData['order_status'] ?? '') !== 'PAID') {
            return response()->json(['error' => 'Payment not completed.'], 400);
        }

        $order = Order::where('cashfree_order_id', $orderId)->first();
        if (!$order) {
            return response()->json(['error' => 'Order not found for this payment. Please contact support.'], 404);
        }

        // Get payment ID from payments list
        $paymentsRes = $this->cashfreeRequest($gateway)
            ->get($this->cashfreeBaseUrl($gateway) . '/orders/' . $orderId . '/payments');

        $cfPaymentId = $paymentsRes->successful()
            ? ($paymentsRes->json()[0]['cf_payment_id'] ?? null)
            : null;

        try {
            $paymentIds = [
                'cashfree_order_id' => $orderId,
                'cashfree_payment_id' => (string)$cfPaymentId,
            ];

            $this->completePaidOrder($order, $paymentIds);
        } catch (\RuntimeException $e) {
            return $this->paidStockFailureResponse($request, 'cashfree', $paymentIds ?? [
                'cashfree_order_id' => $orderId,
                'cashfree_payment_id' => (string)$cfPaymentId,
            ], $e);
        }

        session()->forget(['cart', 'checkout_data', 'coupon', 'stock_reservation_token', 'stock_reservation_expires_at', 'cashfree_pending_order_id', 'pending_order_number']);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'redirect_url' => route('order.success', $order->order_number)]);
        }

        return redirect()->route('order.success', $order->order_number);
    }

    // ── COD: Place Order Directly ──────────────────────────────────────
    public function razorpayWebhook(Request $request)
    {
        $secret = (string) config('razorpay.webhook_secret');
        if ($secret === '') {
            Log::error('Razorpay webhook secret is not configured.');
            return response()->json(['error' => 'Webhook secret is not configured.'], 500);
        }

        $body = $request->getContent();
        $signature = (string) $request->header('X-Razorpay-Signature', '');
        $expected = hash_hmac('sha256', $body, $secret);

        if ($signature === '' || !hash_equals($expected, $signature)) {
            Log::warning('Razorpay webhook signature verification failed.');
            return response()->json(['error' => 'Invalid signature.'], 400);
        }

        $payload = $request->json()->all();
        $event = (string) ($payload['event'] ?? '');
        $payment = $payload['payload']['payment']['entity'] ?? [];
        $gatewayOrder = $payload['payload']['order']['entity'] ?? [];
        $razorpayOrderId = $payment['order_id'] ?? $gatewayOrder['id'] ?? null;
        $razorpayPaymentId = $payment['id'] ?? null;

        if (!$razorpayOrderId) {
            return response()->json(['received' => true]);
        }

        $order = Order::where('razorpay_order_id', $razorpayOrderId)->first();
        if (!$order) {
            Log::warning('Razorpay webhook order not found.', ['razorpay_order_id' => $razorpayOrderId, 'event' => $event]);
            return response()->json(['received' => true]);
        }

        if (in_array($event, ['payment.captured', 'order.paid'], true) || ($payment['status'] ?? '') === 'captured') {
            $this->completePaidOrder($order, [
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
            ]);
        } elseif ($event === 'payment.failed' || ($payment['status'] ?? '') === 'failed') {
            $this->failPendingOrder($order, $payment['error_description'] ?? 'Razorpay payment failed');
        }

        return response()->json(['received' => true]);
    }

    public function cashfreeWebhook(Request $request)
    {
        $secret = (string) (config('cashfree.webhook_secret') ?: config('cashfree.secret_key'));
        if ($secret === '') {
            Log::error('Cashfree webhook secret is not configured.');
            return response()->json(['error' => 'Webhook secret is not configured.'], 500);
        }

        $body = $request->getContent();
        $timestamp = (string) $request->header('x-webhook-timestamp', '');
        $signature = (string) $request->header('x-webhook-signature', '');
        $expected = base64_encode(hash_hmac('sha256', $timestamp . $body, $secret, true));

        if ($timestamp === '' || $signature === '' || !hash_equals($expected, $signature)) {
            Log::warning('Cashfree webhook signature verification failed.');
            return response()->json(['error' => 'Invalid signature.'], 400);
        }

        $payload = $request->json()->all();
        $type = strtoupper((string) ($payload['type'] ?? $payload['event'] ?? ''));
        $orderData = $payload['data']['order'] ?? $payload['order'] ?? [];
        $paymentData = $payload['data']['payment'] ?? $payload['payment'] ?? [];
        $cashfreeOrderId = $orderData['order_id'] ?? $paymentData['order_id'] ?? null;
        $cashfreePaymentId = $paymentData['cf_payment_id'] ?? $paymentData['payment_id'] ?? null;
        $paymentStatus = strtoupper((string) ($paymentData['payment_status'] ?? $orderData['order_status'] ?? ''));

        if (!$cashfreeOrderId) {
            return response()->json(['received' => true]);
        }

        $order = Order::where('cashfree_order_id', $cashfreeOrderId)->first();
        if (!$order) {
            Log::warning('Cashfree webhook order not found.', ['cashfree_order_id' => $cashfreeOrderId, 'type' => $type]);
            return response()->json(['received' => true]);
        }

        if (str_contains($type, 'SUCCESS') || in_array($paymentStatus, ['SUCCESS', 'PAID'], true)) {
            $this->completePaidOrder($order, [
                'cashfree_order_id' => $cashfreeOrderId,
                'cashfree_payment_id' => (string) $cashfreePaymentId,
            ]);
        } elseif (str_contains($type, 'FAILED') || in_array($paymentStatus, ['FAILED', 'CANCELLED', 'USER_DROPPED'], true)) {
            $this->failPendingOrder($order, 'Cashfree payment failed');
        }

        return response()->json(['received' => true]);
    }

    public function createCodOrder(Request $request)
    {
        if (!$this->enabledGateway('cod')) {
            return response()->json(['error' => 'Cash on Delivery is disabled. Please select an enabled payment method.'], 422);
        }

        $this->validateCheckoutRequest($request);

        $cart = session()->get('cart', []);
        if (empty($cart))
            return response()->json(['error' => 'Your cart is empty.'], 422);

        $stockError = $this->checkStock($cart);
        if ($stockError)
            return response()->json($stockError, 422);

        $this->storeCheckoutSession($request);

        try {
            $orderNumber = $this->createOrderInDB(
                session()->get('checkout_data'),
                $cart,
                'cod',
                ['payment_status' => 'pending'] // override: COD not paid yet
            );
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        // Send order success email
        $order = Order::where('order_number', $orderNumber)->first();
        try {
            Mail::to($order->email)->send(new OrderSuccess($order));
        } catch (\Exception $e) {
            Log::error('Failed to send order success email: ' . $e->getMessage());
        }

        return response()->json([
            'success'      => true,
            'redirect_url' => route('order.success', $orderNumber),
        ]);
    }

    // ── Payment Failed ─────────────────────────────────────────────────
    public function paymentFailed(Request $request)
    {
        $customer = $this->customer();

        if (!$customer) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $checkoutData = session()->get('checkout_data');
        $cart = session()->get('cart', []);

        // Create a failed order record for tracking
        if ($checkoutData && !empty($cart)) {
            try {
                $order = DB::transaction(function () use ($checkoutData, $cart, $request, $customer) {
                    $coupon = session()->get('coupon');
                    $totals = $this->calculateCartTotals($cart, $coupon);

                    $order = Order::create([
                        'customer_id' => $customer->id,
                        'order_number' => 'BB-' . strtoupper(uniqid()),
                        'name' => $checkoutData['name'],
                        'phone' => $checkoutData['phone'],
                        'email' => $checkoutData['email'] ?? $customer->email,
                        'address' => $checkoutData['address'],
                        'city' => $checkoutData['city'],
                        'state' => $checkoutData['state'],
                        'pincode' => $checkoutData['pincode'],
                        'notes' => $checkoutData['notes'] ?? null,
                        'total_amount' => $totals['total'],
                        'shipping_amount' => $totals['shippingTotal'],
                        'tax_amount' => $totals['taxAmount'],
                        'coupon_id' => $coupon['id'] ?? null,
                        'discount_amount' => $totals['discount'],
                        'net_amount' => $totals['total'],
                        'status' => 'cancelled',
                        'payment_status' => 'failed',
                        'payment_gateway' => $request->input('gateway', 'unknown'),
                    ]);

                    // Add order items
                    foreach ($cart as $item) {
                        $product = Product::find($item['product_id']);
                        $gstRate = (float) ($product?->tax_rate ?? $product?->gst_rate ?? 0);
                        $lineSubtotal = (float) $item['price'] * (int) $item['quantity'];
                        $lineTax = $gstRate > 0 ? ($lineSubtotal * $gstRate / 100) : 0;

                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $item['product_id'],
                            'variation_id' => $item['variation_id'] ?? null,
                            'product_name' => $item['name'],
                            'variation_name' => $item['variation'] ?? null,
                            'sku' => $item['sku'] ?? null,
                            'unit_price' => $item['price'],
                            'quantity' => $item['quantity'],
                            'subtotal' => $lineSubtotal,
                            'shipping_charge' => $item['shipping_charge'] ?? 0,
                            'gst_rate' => $gstRate,
                            'tax_amount' => $lineTax,
                            'net_price' => $lineSubtotal,
                        ]);
                    }

                    return $order;
                });
            } catch (\Throwable $e) {
                Log::error('Failed to create failed order record: ' . $e->getMessage());

                return response()->json(['error' => 'Payment failed, but the failed order record could not be saved.'], 500);
            }

            // Send order failed email
            try {
                Mail::to($order->email)->send(new OrderFailed($order, 'Payment was cancelled or failed'));
            } catch (\Exception $e) {
                Log::error('Failed to send order failed email: ' . $e->getMessage());
            }

            if ($reservationToken = session()->get('stock_reservation_token')) {
                $this->releaseStockReservation($reservationToken);
            }

            // Clear cart and checkout data
            session()->forget(['cart', 'checkout_data', 'coupon', 'stock_reservation_token', 'stock_reservation_expires_at']);
        }

        return response()->json(['error' => 'Payment was cancelled or failed. No order has been placed.'], 400);
    }

    // ── Order Success ──────────────────────────────────────────────────
    public function success($orderNumber)
    {
        $order = Order::with('items.product')
            ->where('order_number', $orderNumber)
            ->where('customer_id', $this->customer()->id)
            ->firstOrFail();
        return view('order-success', compact('order'));
    }

    // ── My Orders ──────────────────────────────────────────────────────
    public function myOrders()
    {
        $orders = Order::with(['items', 'orderReturn'])
            ->where('customer_id', $this->customer()->id)
            ->latest()
            ->paginate(10);
        return view('my-orders', compact('orders'));
    }

    // ── Order Detail ───────────────────────────────────────────────────
    public function show($orderNumber)
    {
        $order = Order::with(['items.product', 'orderReturn'])
            ->where('order_number', $orderNumber)
            ->where('customer_id', $this->customer()->id)
            ->firstOrFail();
        return view('order-detail', compact('order'));
    }
}
