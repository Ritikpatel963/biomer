<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Order;      // ✅ add this
use App\Models\Wishlist;   // ✅ add this

class Customer extends Authenticatable
{
    use Notifiable;

    protected $guard = 'customer';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'audience_type',
        'address',
        'city',
        'state',
        'pincode',
        'country',
        'password',
    ];
    protected $hidden   = ['password', 'remember_token'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'customer_id');
    }

    public function blogReviews()
    {
        return $this->hasMany(BlogReview::class, 'customer_id');
    }

    public function audiencePreferences()
    {
        return $this->hasMany(AudiencePreference::class, 'customer_id');
    }

    public function hasInWishlist(int $productId): bool
    {
        return $this->wishlists()->where('product_id', $productId)->exists();
    }
}
