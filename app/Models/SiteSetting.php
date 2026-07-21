<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    private const DEFAULT_LOGO = 'assets/bharat-biomer/bblogo.webp';
    private const DEFAULT_PRELOADER_LOGO = 'assets/images/home-img/bb logo.png';
    private const DEFAULT_FOOTER_LOGO = 'assets/bharat-biomer/footer-logo.svg';

    protected $fillable = [
        'site_name', 'tagline', 'email', 'phone', 'address', 'about',
        'facebook_url', 'twitter_url', 'instagram_url', 'linkedin_url',
        'google_analytics_id', 'homepage_video_url', 'homepage_video_title',
        'homepage_video_caption', 'instagram_embed_code',
        'logo_path', 'footer_logo_path', 'footer_text',
    ];

    /**
     * Get a setting by key or return default
     */
    public static function get($key, $default = null)
    {
        $setting = self::current();
        return $setting?->{$key} ?? $default;
    }

    public static function current(): ?self
    {
        return self::query()->orderByDesc('id')->first();
    }

    public function getLogoUrlAttribute(): string
    {
        return $this->logo_path ? asset('storage/' . $this->logo_path) : asset(self::DEFAULT_LOGO);
    }

    public function getPreloaderLogoUrlAttribute(): string
    {
        return $this->logo_path ? asset('storage/' . $this->logo_path) : asset(self::DEFAULT_PRELOADER_LOGO);
    }

    public function getFooterLogoUrlAttribute(): string
    {
        return $this->footer_logo_path ? asset('storage/' . $this->footer_logo_path) : asset(self::DEFAULT_FOOTER_LOGO);
    }
}
