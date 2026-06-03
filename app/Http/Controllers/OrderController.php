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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use App\Services\CartPricingService;
use App\Services\ShiprocketService;


class OrderController extends Controller
{
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
        return view('checkout', array_merge($totals, compact('cart', 'coupon', 'customer', 'paymentGateways')));
    }

    // ── Shared: Validate & Check Stock ────────────────────────────────
    private function validateCheckoutRequest(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'notes' => 'nullable|string|max:500',
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

    private function createOrderInDB(array $checkoutData, array $cart, string $gateway, array $paymentIds): string
    {
        $orderNumber = null;

        $this->syncCustomerAddress($checkoutData);

        DB::transaction(function () use ($checkoutData, $cart, $gateway, $paymentIds, &$orderNumber) {
            $customer = $this->customer();
            $coupon = $this->validatedCouponForOrder($cart);
            $totals = $this->calculateCartTotals($cart, $coupon);

            $this->reserveStock($cart);

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
            session()->forget(['cart', 'checkout_data', 'coupon']);
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

    // ── RAZORPAY: Step 1 — Create Order ───────────────────────────────
    public function createRazorpayOrder(Request $request)
    {
        $this->validateCheckoutRequest($request);

        $cart = session()->get('cart', []);
        if (empty($cart))
            return response()->json(['error' => 'Your cart is empty.'], 422);

        $stockError = $this->checkStock($cart);
        if ($stockError)
            return response()->json($stockError, 422);

        $coupon = session()->get('coupon');
        $totals = $this->calculateCartTotals($cart, $coupon);
        $total = $totals['total'];

        $api = new Api(config('razorpay.key_id'), config('razorpay.key_secret'));
        $razorpayOrder = $api->order->create([
            'receipt' => 'BB-' . strtoupper(uniqid()),
            'amount' => (int)round($total * 100),
            'currency' => 'INR',
        ]);

        $this->storeCheckoutSession($request);
        $customer = $this->customer();

        return response()->json([
            'success' => true,
            'razorpay_order_id' => $razorpayOrder->id,
            'amount' => (int)round($total * 100),
            'currency' => 'INR',
            'key_id' => config('razorpay.key_id'),
            'name' => $customer->name,
            'email' => $customer->email,
            'phone' => $customer->phone ?? $request->phone,
        ]);
    }

    // ── RAZORPAY: Step 2 — Verify & Save Order ────────────────────────
    public function paymentSuccess(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $api = new Api(config('razorpay.key_id'), config('razorpay.key_secret'));
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

        $checkoutData = session()->get('checkout_data');
        if (!$checkoutData)
            return response()->json(['error' => 'Session expired. Please try again.'], 422);

        $cart = session()->get('cart', []);
        if (empty($cart))
            return response()->json(['error' => 'Your cart is empty.'], 422);

        $coupon = session()->get('coupon');
        $totals = $this->calculateCartTotals($cart, $coupon);
        $total = $totals['total'];

        try {
            $orderNumber = $this->createOrderInDB($checkoutData, $cart, 'razorpay', [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
            ]);
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

        return response()->json(['success' => true, 'redirect_url' => route('order.success', $orderNumber)]);
    }

    // ── CASHFREE: Step 1 — Create Order ───────────────────────────────
    public function createCashfreeOrder(Request $request)
    {
        $this->validateCheckoutRequest($request);

        $cart = session()->get('cart', []);
        if (empty($cart))
            return response()->json(['error' => 'Your cart is empty.'], 422);

        $stockError = $this->checkStock($cart);
        if ($stockError)
            return response()->json($stockError, 422);

        $coupon = session()->get('coupon');
        $totals = $this->calculateCartTotals($cart, $coupon);
        $total = $totals['total'];
        $customer = $this->customer();
        $orderId = 'BB-' . strtoupper(uniqid());

        if (empty(config('cashfree.app_id')) || empty(config('cashfree.secret_key'))) {
            return response()->json(['error' => 'Cashfree credentials are not configured. Please set CASHFREE_APP_ID and CASHFREE_SECRET_KEY.'], 500);
        }

        $returnUrl = $request->getSchemeAndHttpHost() . route('order.cashfree.verify', [], false) . '?order_id={order_id}';

        $response = Http::withHeaders([
            'x-client-id' => config('cashfree.app_id'),
            'x-client-secret' => config('cashfree.secret_key'),
            'x-api-version' => '2023-08-01',
            'Content-Type' => 'application/json',
        ])->post(config('cashfree.base_url') . '/orders', [
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
            Log::error('Cashfree create order missing payment_session_id: ' . json_encode($data));
            return response()->json(['error' => 'Cashfree did not return a valid payment_session_id. Please check Cashfree configuration and logs.'], 500);
        }

        return response()->json([
            'success' => true,
            'payment_session_id' => $data['payment_session_id'],
            'cashfree_order_id' => $data['order_id'],
        ]);
    }

    // ── CASHFREE: Step 2 — Verify & Save Order ────────────────────────
    public function verifyCashfreePayment(Request $request)
    {
        $orderId = $request->input('order_id');
        if (!$orderId)
            return response()->json(['error' => 'Missing order ID.'], 422);

        // Verify with Cashfree server-side
        $response = Http::withHeaders([
            'x-client-id' => config('cashfree.app_id'),
            'x-client-secret' => config('cashfree.secret_key'),
            'x-api-version' => '2023-08-01',
        ])->get(config('cashfree.base_url') . '/orders/' . $orderId);

        if (!$response->successful()) {
            Log::error('Cashfree verify order failed: ' . $response->body());
            return response()->json(['error' => 'Could not verify payment with Cashfree. Please check the Cashfree response logs.'], 500);
        }

        $orderData = $response->json();

        if (($orderData['order_status'] ?? '') !== 'PAID') {
            return response()->json(['error' => 'Payment not completed.'], 400);
        }

        $checkoutData = session()->get('checkout_data');
        if (!$checkoutData)
            return response()->json(['error' => 'Session expired. Please try again.'], 422);

        $cart = session()->get('cart', []);
        if (empty($cart))
            return response()->json(['error' => 'Your cart is empty.'], 422);

        $coupon = session()->get('coupon');
        $totals = $this->calculateCartTotals($cart, $coupon);
        $total = $totals['total'];

        // Get payment ID from payments list
        $paymentsRes = Http::withHeaders([
            'x-client-id' => config('cashfree.app_id'),
            'x-client-secret' => config('cashfree.secret_key'),
            'x-api-version' => '2023-08-01',
        ])->get(config('cashfree.base_url') . '/orders/' . $orderId . '/payments');

        $cfPaymentId = $paymentsRes->successful()
            ? ($paymentsRes->json()[0]['cf_payment_id'] ?? null)
            : null;

        try {
            $orderNumber = $this->createOrderInDB($checkoutData, $cart, 'cashfree', [
                'cashfree_order_id' => $orderId,
                'cashfree_payment_id' => (string)$cfPaymentId,
            ]);
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

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'redirect_url' => route('order.success', $orderNumber)]);
        }

        return redirect()->route('order.success', $orderNumber);
    }

    // ── COD: Place Order Directly ──────────────────────────────────────
    public function createCodOrder(Request $request)
    {
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
        $checkoutData = session()->get('checkout_data');
        $cart = session()->get('cart', []);

        // Create a failed order record for tracking
        if ($checkoutData && !empty($cart)) {
            $coupon = session()->get('coupon');
            $totals = $this->calculateCartTotals($cart, $coupon);
            $total = $totals['total'];

            $customer = $this->customer();
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
                'notes' => $checkoutData['notes'],
                'total_amount' => $total,
                'shipping_amount' => collect($cart)->sum(fn($item) => ($item['shipping_charge'] ?? 0) * $item['quantity']),
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

            // Send order failed email
            try {
                Mail::to($order->email)->send(new OrderFailed($order, 'Payment was cancelled or failed'));
            } catch (\Exception $e) {
                Log::error('Failed to send order failed email: ' . $e->getMessage());
            }

            // Clear cart and checkout data
            session()->forget(['cart', 'checkout_data', 'coupon']);
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
