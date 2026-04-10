<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReturn extends Model
{
    protected $fillable = [
        'order_id',
        'order_item_id',
        'customer_id',
        'reason',
        'return_reason',
        'description',
        'customer_notes',
        'status',
        'refund_amount',
        'return_tracking_number',
        'approved_at',
        'requested_at',
        'refunded_at',
        'admin_notes',
    ];

    protected $casts = [
        'refund_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'requested_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    // Backward-compatible aliases for the actual DB columns.
    public function getReasonAttribute()
    {
        return $this->attributes['return_reason'] ?? null;
    }

    public function setReasonAttribute($value): void
    {
        $this->attributes['return_reason'] = $value;
    }

    public function getDescriptionAttribute()
    {
        return $this->attributes['customer_notes'] ?? null;
    }

    public function setDescriptionAttribute($value): void
    {
        $this->attributes['customer_notes'] = $value;
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }
}
