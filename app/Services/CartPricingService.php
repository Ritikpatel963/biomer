<?php

namespace App\Services;

use App\Models\Product;

class CartPricingService
{
    public function calculate(array $cart, ?array $coupon = null): array
    {
        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $shippingTotal = collect($cart)->sum(fn ($item) => ($item['shipping_charge'] ?? 0) * $item['quantity']);
        $discount = $this->discountFor($subtotal, $coupon);
        $taxAmount = $this->taxFor($cart);
        $total = max(0, $subtotal - $discount) + $shippingTotal + $taxAmount;

        return [
            'subtotal' => $subtotal,
            'shippingTotal' => $shippingTotal,
            'discount' => $discount,
            'taxAmount' => $taxAmount,
            'total' => $total,
        ];
    }

    private function discountFor(float $subtotal, ?array $coupon): float
    {
        if (!$coupon) {
            return 0;
        }

        $discount = $coupon['type'] === 'percent'
            ? ($subtotal * ($coupon['value'] / 100))
            : $coupon['value'];

        return min($subtotal, max(0, (float) $discount));
    }

    private function taxFor(array $cart): float
    {
        $productIds = collect($cart)->pluck('product_id')->filter()->unique()->values();
        $products = Product::query()
            ->whereIn('id', $productIds)
            ->get(['id', 'tax_rate'])
            ->keyBy('id');

        return collect($cart)->sum(function ($item) use ($products) {
            $product = $products->get($item['product_id']);

            if (!$product || $product->tax_rate <= 0) {
                return 0;
            }

            return ($item['price'] * $item['quantity']) * ($product->tax_rate / 100);
        });
    }
}
