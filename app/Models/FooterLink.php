<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class FooterLink extends Model
{
    protected $fillable = ['section', 'label', 'url', 'position', 'is_active', 'target'];

    /**
     * Get active footer links by section sorted by position
     */
    public static function getBySection($section)
    {
        return self::where('section', $section)
                   ->where('is_active', true)
                   ->orderBy('position')
                   ->get();
    }

    /**
     * Get active footer links grouped by section
     */
    public static function getActiveGrouped()
    {
        return self::where('is_active', true)
                   ->orderBy('section')
                   ->orderBy('position')
                   ->get()
                   ->groupBy('section');
    }

    /**
     * Get all sections
     */
    public static function getSections()
    {
        return self::where('is_active', true)->distinct('section')->pluck('section')->unique();
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
