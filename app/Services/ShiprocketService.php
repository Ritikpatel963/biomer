<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShiprocketService
{
    protected string $baseUrl;
    protected string $email;
    protected string $password;
    protected string $pickupLocation;

    public function __construct()
    {
        $this->baseUrl = config('shiprocket.base_url');
        $this->email = config('shiprocket.email');
        $this->password = config('shiprocket.password');
        $this->pickupLocation = config('shiprocket.pickup_location');
    }

    private function getToken(): ?string
    {
        $cachedToken = Cache::get('shiprocket.token');
        if ($cachedToken) {
            return $cachedToken;
        }

        try {
            $response = Http::timeout(10)->post($this->baseUrl . '/auth/login', [
                'email' => $this->email,
                'password' => $this->password,
            ]);

            $token = $response->json('token');
            if ($response->successful() && $token) {
                Cache::put('shiprocket.token', $token, now()->addHours(23));

                return $token;
            }

            Log::error('Shiprocket auth failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Shiprocket auth failed: ' . $e->getMessage());
        }

        return null;
    }

    public function createOrder(Order $order): array
    {
        $token = $this->getToken();
        if (!$token) {
            return [];
        }

        $items = $order->items->map(function ($item) {
            return [
                'name' => $item->product_name,
                'sku' => $item->sku ?? 'SKU-' . $item->id,
                'units' => $item->quantity,
                'selling_price' => round((float) $item->unit_price, 2),
                'discount' => 0,
                'tax' => 0,
                'hsn' => null,
                'weight' => 1,
            ];
        })->toArray();

        $payload = [
            'order_id' => $order->order_number,
            'order_date' => $order->created_at->toDateTimeString(),
            'pickup_location' => $this->pickupLocation,
            'channel_id' => 1,
            'billing_customer_name' => $order->name,
            'billing_address' => $order->address,
            'billing_city' => $order->city,
            'billing_pincode' => $order->pincode,
            'billing_state' => $order->state,
            'billing_country' => 'India',
            'billing_email' => $order->email,
            'billing_phone' => $order->phone,
            'shipping_is_billing' => true,
            'shipping_customer_name' => $order->name,
            'shipping_address' => $order->address,
            'shipping_city' => $order->city,
            'shipping_pincode' => $order->pincode,
            'shipping_state' => $order->state,
            'shipping_country' => 'India',
            'shipping_charges' => round((float) $order->shipping_amount, 2),
            'sub_total' => round($order->items->sum('subtotal'), 2),
            'length' => 10,
            'breadth' => 10,
            'height' => 10,
            'weight' => max(1, $order->items->sum('quantity')),
            'order_items' => $items,
        ];

        try {
            $response = Http::withToken($token)
                ->post($this->baseUrl . '/orders/create/adhoc', $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Shiprocket order failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Shiprocket create order exception: ' . $e->getMessage());
        }

        return [];
    }
}
