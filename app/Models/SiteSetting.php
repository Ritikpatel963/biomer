<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name', 'tagline', 'email', 'phone', 'address', 'about',
        'facebook_url', 'twitter_url', 'instagram_url', 'linkedin_url',
        'google_analytics_id', 'homepage_video_url', 'homepage_video_title',
        'homepage_video_caption', 'instagram_embed_code',
        'logo_path', 'footer_logo_path', 'footer_text',
        'home_banner_image_1', 'home_banner_image_2', 'home_banner_image_3', 'home_banner_image_4'
    ];

    /**
     * Get a setting by key or return default
     */
    public static function get($key, $default = null)
    {
        $setting = self::first();
        return $setting?->{$key} ?? $default;
    }
}
