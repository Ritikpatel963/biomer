<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogReview extends Model
{
    protected $fillable = [
        'blog_id',
        'customer_id',
        'name',
        'email',
        'rating',
        'comment',
        'status',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
