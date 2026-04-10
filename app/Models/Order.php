<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id', 'order_number', 'name', 'phone', 'email',
        'address', 'city', 'state', 'pincode', 'notes',
        'total_amount', 'shipping_amount', 'tax_amount', 'status',
        'razorpay_order_id', 'razorpay_payment_id', 'payment_status',
        'payment_gateway', 'cashfree_order_id', 'cashfree_payment_id',
        'shiprocket_order_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class , 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderReturn()
    {
        return $this->hasOne(OrderReturn::class);
    }

    public function canRequestReturn(): bool
    {
        if ($this->status !== 'delivered') {
            return false;
        }

        if ($this->orderReturn()->exists()) {
            return false;
        }

        if ($this->delivered_at) {
            return $this->delivered_at->diffInDays(now()) <= 30;
        }

        return true;
    }
}
