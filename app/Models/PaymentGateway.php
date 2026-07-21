<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = [
        'gateway_name',
        'display_name',
        'logo_url',
        'is_enabled',
        'environment',
        'api_key',
        'secret_key',
        'additional_config'
    ];

    protected $hidden = [
        'api_key',
        'secret_key',
    ];

    protected $casts = [
        'additional_config' => 'array',
        'is_enabled'        => 'boolean',
        'api_key'           => 'encrypted',    // AES-256 encrypted at rest via APP_KEY
        'secret_key'        => 'encrypted',    // AES-256 encrypted at rest via APP_KEY
    ];

    /**
     * Get enabled payment gateways
     */
    public static function getEnabled()
    {
        return self::where('is_enabled', true)->get();
    }

    /**
     * Check if specific gateway is enabled
     */
    public static function isEnabled($gatewayName)
    {
        return self::where('gateway_name', $gatewayName)
                   ->where('is_enabled', true)
                   ->exists();
    }

    /**
     * Get gateway by name
     */
    public static function getByName($gatewayName)
    {
        return self::where('gateway_name', $gatewayName)->first();
    }
}
