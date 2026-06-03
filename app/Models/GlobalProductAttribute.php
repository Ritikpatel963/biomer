<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GlobalProductAttribute extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'values',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'values' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (self $attribute) {
            $attribute->slug = $attribute->slug ?: Str::slug($attribute->name);
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
