<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class HeaderLink extends Model
{
    protected $fillable = ['label', 'url', 'icon', 'position', 'is_active', 'target'];

    /**
     * Get active header links sorted by position
     */
    public static function getActive()
    {
        return self::where('is_active', true)->orderBy('position')->get();
    }

    public function getHrefAttribute(): string
    {
        return self::resolveUrl($this->url);
    }

    public function getSafeTargetAttribute(): string
    {
        return $this->target === '_blank' ? '_blank' : '_self';
    }

    protected static function resolveUrl(?string $url): string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return '#';
        }

        if (preg_match('/^(https?:)?\/\//i', $url) || preg_match('/^(mailto|tel):/i', $url) || str_starts_with($url, '#')) {
            return $url;
        }

        if (Route::has($url)) {
            return route($url);
        }

        return url($url);
    }
}
