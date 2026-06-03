<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $fillable = [
        'product_id', 'sku', 'attribute_name', 'attribute_value',
        'attributes', 'price', 'compare_at_price', 'cost_price', 'weight', 'unit',
        'stock_quantity', 'track_stock', 'is_in_stock', 'is_active', 'is_default', 'image_path',
    ];

    protected $casts = [
        'attributes'       => 'array',
        'price'            => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'cost_price'       => 'decimal:2',
        'weight'           => 'decimal:2',
        'track_stock'      => 'boolean',
        'is_in_stock'      => 'boolean',
        'is_active'        => 'boolean',
        'is_default'       => 'boolean',
        'stock_quantity'   => 'integer',
    ];

    public function product() { return $this->belongsTo(Product::class); }

    // ── Stock Helpers ──────────────────────────────────────
    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function isLowStock(int $threshold = 5): bool
    {
        return $this->stock_quantity <= $threshold && $this->stock_quantity > 0;
    }
}
