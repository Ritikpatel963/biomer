<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'sku', 'brand_id', 'category_id',
        'technical_content', 'description', 'short_description',
        'base_price', 'unit', 'shipping_charge', 'tax_rate', 'gst_rate', 'stock_quantity', 'manage_stock', 'status',
        'featured_image', 'video_url', 'meta_title', 'meta_description', 'meta_keyword',
    ];

    protected $casts = [
        'base_price'     => 'decimal:2',
        'shipping_charge'=> 'decimal:2',
        'tax_rate'       => 'decimal:2',
        'gst_rate'       => 'decimal:2',
        'manage_stock'   => 'boolean',
        'stock_quantity' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->slug ??= Str::slug($m->name));
    }

    // ── Relationships ──────────────────────────────────────
    public function brand()      { return $this->belongsTo(Brand::class); }
    public function category()   { return $this->belongsTo(Category::class); }
    public function tags()       { return $this->belongsToMany(Tag::class, 'product_tag'); }
    public function images()     { return $this->hasMany(ProductImage::class)->orderBy('sort_order'); }
    public function variations()      { return $this->hasMany(ProductVariation::class); }
    public function attributes()      { return $this->hasMany(ProductAttribute::class); }
    public function reviews()           { return $this->hasMany(ProductReview::class); }
    public function approvedReviews()   { return $this->hasMany(ProductReview::class)->where('status', 'approved'); }

    // ── Stock Helpers ──────────────────────────────────────
    public function isInStock(): bool
    {
        if (!$this->manage_stock) return true;

        // If has variations — check variation stock
        if ($this->variations->count() > 0) {
            return $this->variations->where('is_active', true)->sum('stock_quantity') > 0;
        }

        return $this->stock_quantity > 0;
    }

    public function isLowStock(int $threshold = 5): bool
    {
        if (!$this->manage_stock) return false;

        if ($this->variations->count() > 0) {
            return $this->variations->where('is_active', true)->sum('stock_quantity') <= $threshold;
        }

        return $this->stock_quantity <= $threshold && $this->stock_quantity > 0;
    }

    // ── Price Helpers ──────────────────────────────────────
    public function getMinPriceAttribute(): float
    {
        return (float) ($this->variations->min('price') ?? $this->base_price);
    }

    public function getMaxPriceAttribute(): float
    {
        return (float) ($this->variations->max('price') ?? $this->base_price);
    }

    public function getPriceRangeAttribute(): string
    {
        $min = $this->min_price;
        $max = $this->max_price;
        return $min === $max
            ? '₹' . number_format($min, 2)
            : '₹' . number_format($min, 2) . ' – ₹' . number_format($max, 2);
    }

    public function getAvgRatingAttribute(): float
    {
        if (array_key_exists('approved_reviews_avg_rating', $this->attributes)) {
            return round((float) $this->attributes['approved_reviews_avg_rating'], 1);
        }

        return round($this->approvedReviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewCountAttribute(): int
    {
        if (array_key_exists('approved_reviews_count', $this->attributes)) {
            return (int) $this->attributes['approved_reviews_count'];
        }

        return $this->approvedReviews()->count();
    }
}
