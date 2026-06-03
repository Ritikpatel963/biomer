<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudiencePreference extends Model
{
    protected $fillable = [
        'customer_id',
        'session_token',
        'audience_type',
        'name',
        'email',
        'phone',
        'source_url',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
