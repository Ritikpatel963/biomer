<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockReservation extends Model
{
    protected $fillable = [
        'customer_id',
        'session_id',
        'token',
        'product_id',
        'variation_id',
        'quantity',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'expires_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }
}
