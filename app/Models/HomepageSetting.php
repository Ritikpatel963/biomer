<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSetting extends Model
{
    protected $fillable = [
        'hero_enabled',
        'hero_slider_enabled',
        'hero_slides',
        'why_bharat_title',
        'why_bharat_items',
        'what_we_do_title',
        'what_we_do_description',
        'what_we_do_label',
        'what_we_do_image_url',
        'what_we_do_items',
        'who_we_serve_title',
        'who_we_serve_items',
        'key_highlights_title',
        'key_highlights_subtitle',
        'key_highlights_items',
        'video_reviews_enabled',
        'video_reviews_title',
        'video_reviews_subtitle',
        'video_reviews_items',
    ];

    protected $casts = [
        'hero_enabled' => 'boolean',
        'hero_slider_enabled' => 'boolean',
        'hero_slides' => 'array',
        'why_bharat_items' => 'array',
        'what_we_do_items' => 'array',
        'who_we_serve_items' => 'array',
        'key_highlights_items' => 'array',
        'video_reviews_enabled' => 'boolean',
        'video_reviews_items' => 'array',
    ];

    public static function defaults(): array
    {
        return [
            'hero_enabled' => true,
            'hero_slider_enabled' => false,
            'hero_slides' => [
                [
                    'headline_prefix' => 'Nature-powered biology for',
                    'headline_highlight' => 'high-performance',
                    'headline_suffix' => 'farming',
                    'description' => 'Bharat Biomer develops advanced biological solutions that improve crop productivity, resilience, and soil health naturally.',
                    'secondary_description' => 'Harnessing beneficial microbes like PPFM to help crops perform better under stress, improve flowering, and reduce chemical dependency.',
                    'primary_button_text' => 'Explore Our Technology',
                    'primary_button_url' => '/technology',
                    'secondary_button_text' => 'Contact Us',
                    'secondary_button_url' => '/contact',
                    'media_type' => 'image',
                    'media_url' => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=900&auto=format&fit=crop&q=80',
                    'is_active' => true,
                ],
                [
                    'headline_prefix' => 'Smarter inputs for',
                    'headline_highlight' => 'resilient',
                    'headline_suffix' => 'crops',
                    'description' => 'Boost crop vigor with targeted biological formulations that work with plant physiology and local field conditions.',
                    'secondary_description' => 'Designed to improve nutrient efficiency, stress tolerance, and consistency across growth stages.',
                    'primary_button_text' => 'View Products',
                    'primary_button_url' => '/products',
                    'secondary_button_text' => 'Learn More',
                    'secondary_button_url' => '/technology',
                    'media_type' => 'image',
                    'media_url' => 'https://images.unsplash.com/photo-1464226184884-fa280b87c399?w=900&auto=format&fit=crop&q=80',
                    'is_active' => true,
                ],
                [
                    'headline_prefix' => 'Microbial science with',
                    'headline_highlight' => 'field-proven',
                    'headline_suffix' => 'results',
                    'description' => 'Our solutions are built to perform in real farm environments and deliver measurable crop response.',
                    'secondary_description' => 'Low-dose, high-impact products that support sustainability and profitability together.',
                    'primary_button_text' => 'Farmer Stories',
                    'primary_button_url' => '/about',
                    'secondary_button_text' => 'Contact Team',
                    'secondary_button_url' => '/contact',
                    'media_type' => 'image',
                    'media_url' => 'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?w=900&auto=format&fit=crop&q=80',
                    'is_active' => true,
                ],
                [
                    'headline_prefix' => 'Built for Indian',
                    'headline_highlight' => 'soil and climate',
                    'headline_suffix' => 'needs',
                    'description' => 'Bharat Biomer innovations align with regional agronomy, helping farmers improve outcomes season after season.',
                    'secondary_description' => 'From flowering support to stress management, every solution is practical and easy to adopt.',
                    'primary_button_text' => 'Get Started',
                    'primary_button_url' => '/contact',
                    'secondary_button_text' => 'Our Mission',
                    'secondary_button_url' => '/about',
                    'media_type' => 'image',
                    'media_url' => 'https://images.unsplash.com/photo-1465379944081-7f47de8d74ac?w=900&auto=format&fit=crop&q=80',
                    'is_active' => true,
                ],
            ],
            'why_bharat_title' => 'Why Bharat Biomer',
            'why_bharat_items' => [
                ['title' => 'Science-driven biological innovation', 'description' => 'Advanced research and development in microbial solutions'],
                ['title' => 'Designed for Indian crops, soil & climate', 'description' => 'Tailored solutions for local agricultural conditions'],
                ['title' => 'Low dosage, high impact solutions', 'description' => 'Maximum efficiency with minimal application'],
                ['title' => 'Sustainable, residue-free, & farmer-friendly', 'description' => 'Environmentally safe and easy to use'],
            ],
            'what_we_do_title' => 'What We Do',
            'what_we_do_description' => 'We develop and commercialize microbial bio-stimulants and biological inputs that improve plant physiology, nutrient efficiency, and resilience against environmental stress.',
            'what_we_do_label' => 'Our solutions work across:',
            'what_we_do_image_url' => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=800&auto=format&fit=crop&q=80',
            'what_we_do_items' => [
                ['label' => 'Field crops'],
                ['label' => 'Horticulture'],
                ['label' => 'Vegetables & fruits'],
                ['label' => 'Flowers & plantation crops'],
            ],
            'who_we_serve_title' => 'Who We Serve',
            'who_we_serve_items' => [
                ['label' => 'Farmers & Progressive Growers'],
                ['label' => 'Farmer Producer Organizations'],
                ['label' => 'Agri-input distributors & retailers'],
                ['label' => 'CSR foundations & NGOs'],
                ['label' => 'Research institutions & policymakers'],
            ],
            'key_highlights_title' => 'Key Highlights',
            'key_highlights_subtitle' => 'Proven benefits for your crops',
            'key_highlights_items' => [
                ['title' => 'Improves flowering & fruit set', 'description' => 'Enhanced reproductive growth for better yields'],
                ['title' => 'Enhances nutrient uptake', 'description' => 'Better absorption and utilization of nutrients'],
                ['title' => 'Reduces heat & drought stress', 'description' => 'Improved resilience against environmental challenges'],
                ['title' => 'Sustainable & residue-free', 'description' => 'Safe for the environment and consumers'],
            ],
            'video_reviews_enabled' => true,
            'video_reviews_title' => 'Parent Video Reviews',
            'video_reviews_subtitle' => 'Share up to 6 Instagram reel reviews from farmers, partners, and dealers. Desktop shows tabs and mobile gets a swipe-friendly carousel.',
            'video_reviews_items' => [],
        ];
    }

    public static function currentMerged(): array
    {
        $defaults = static::defaults();
        $record = static::first();

        if (!$record) {
            return $defaults;
        }

        return [
            'hero_enabled' => (bool) $record->hero_enabled,
            'hero_slider_enabled' => (bool) $record->hero_slider_enabled,
            'hero_slides' => static::mergeSlides($record->hero_slides, $defaults['hero_slides']),
            'why_bharat_title' => $record->why_bharat_title ?: $defaults['why_bharat_title'],
            'why_bharat_items' => static::mergeItems($record->why_bharat_items, $defaults['why_bharat_items'], ['title', 'description']),
            'what_we_do_title' => $record->what_we_do_title ?: $defaults['what_we_do_title'],
            'what_we_do_description' => $record->what_we_do_description ?: $defaults['what_we_do_description'],
            'what_we_do_label' => $record->what_we_do_label ?: $defaults['what_we_do_label'],
            'what_we_do_image_url' => $record->what_we_do_image_url ?: $defaults['what_we_do_image_url'],
            'what_we_do_items' => static::mergeItems($record->what_we_do_items, $defaults['what_we_do_items'], ['label']),
            'who_we_serve_title' => $record->who_we_serve_title ?: $defaults['who_we_serve_title'],
            'who_we_serve_items' => static::mergeItems($record->who_we_serve_items, $defaults['who_we_serve_items'], ['label']),
            'key_highlights_title' => $record->key_highlights_title ?: $defaults['key_highlights_title'],
            'key_highlights_subtitle' => $record->key_highlights_subtitle ?: $defaults['key_highlights_subtitle'],
            'key_highlights_items' => static::mergeItems($record->key_highlights_items, $defaults['key_highlights_items'], ['title', 'description']),
            'video_reviews_enabled' => (bool) $record->video_reviews_enabled,
            'video_reviews_title' => $record->video_reviews_title ?: $defaults['video_reviews_title'],
            'video_reviews_subtitle' => $record->video_reviews_subtitle ?: $defaults['video_reviews_subtitle'],
            'video_reviews_items' => static::mergeVideoItems($record->video_reviews_items),
        ];
    }

    public static function instagramEmbedUrl(?string $url): ?string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return null;
        }

        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');
        $segments = $path === '' ? [] : explode('/', $path);

        if (count($segments) < 2) {
            return null;
        }

        $type = $segments[0];
        $shortcode = $segments[1] ?? null;

        if (!$shortcode || !in_array($type, ['reel', 'p', 'tv'], true)) {
            return null;
        }

        return 'https://www.instagram.com/' . $type . '/' . $shortcode . '/embed';
    }

    protected static function mergeSlides(?array $items, array $defaults): array
    {
        $items = is_array($items) ? array_values($items) : [];
        $merged = [];

        foreach (range(0, 3) as $index) {
            $default = $defaults[$index] ?? $defaults[0];
            $item = $items[$index] ?? [];
            $merged[] = [
                'headline_prefix' => trim((string) ($item['headline_prefix'] ?? $default['headline_prefix'] ?? '')),
                'headline_highlight' => trim((string) ($item['headline_highlight'] ?? $default['headline_highlight'] ?? '')),
                'headline_suffix' => trim((string) ($item['headline_suffix'] ?? $default['headline_suffix'] ?? '')),
                'description' => trim((string) ($item['description'] ?? $default['description'] ?? '')),
                'secondary_description' => trim((string) ($item['secondary_description'] ?? $default['secondary_description'] ?? '')),
                'primary_button_text' => trim((string) ($item['primary_button_text'] ?? $default['primary_button_text'] ?? '')),
                'primary_button_url' => trim((string) ($item['primary_button_url'] ?? $default['primary_button_url'] ?? '')),
                'secondary_button_text' => trim((string) ($item['secondary_button_text'] ?? $default['secondary_button_text'] ?? '')),
                'secondary_button_url' => trim((string) ($item['secondary_button_url'] ?? $default['secondary_button_url'] ?? '')),
                'media_type' => in_array(($item['media_type'] ?? $default['media_type'] ?? 'image'), ['image', 'video'], true) ? ($item['media_type'] ?? $default['media_type']) : 'image',
                'media_url' => trim((string) ($item['media_url'] ?? $default['media_url'] ?? '')),
                'is_active' => (bool) ($item['is_active'] ?? $default['is_active'] ?? false),
            ];
        }

        return $merged;
    }

    protected static function mergeItems(?array $items, array $defaults, array $keys): array
    {
        $items = is_array($items) ? array_values($items) : [];
        $merged = [];

        foreach ($defaults as $index => $default) {
            $item = $items[$index] ?? [];
            $row = [];

            foreach ($keys as $key) {
                $row[$key] = trim((string) ($item[$key] ?? $default[$key] ?? ''));
            }

            $merged[] = $row;
        }

        return $merged;
    }

    protected static function mergeVideoItems(?array $items): array
    {
        $items = is_array($items) ? array_values($items) : [];
        $merged = [];

        foreach (array_slice($items, 0, 6) as $item) {
            $instagramUrl = trim((string) ($item['instagram_url'] ?? ''));
            if ($instagramUrl === '') {
                continue;
            }

            $isDirectVideo = (bool) preg_match('/\.(mp4|webm|ogg)(\?.*)?$/i', $instagramUrl);
            $videoUrl = null;

            if ($isDirectVideo) {
                if (str_starts_with($instagramUrl, 'http://') || str_starts_with($instagramUrl, 'https://')) {
                    $videoUrl = $instagramUrl;
                } else {
                    $normalizedPath = ltrim($instagramUrl, '/');
                    if (str_starts_with($normalizedPath, 'storage/')) {
                        $videoUrl = asset($normalizedPath);
                    } elseif (str_starts_with($normalizedPath, 'public/')) {
                        $videoUrl = asset('storage/' . ltrim(substr($normalizedPath, 7), '/'));
                    } else {
                        $videoUrl = asset('storage/' . $normalizedPath);
                    }
                }
            }

            $merged[] = [
                'title' => trim((string) ($item['title'] ?? 'Video Review')),
                'instagram_url' => $instagramUrl,
                'embed_url' => static::instagramEmbedUrl($instagramUrl),
                'video_url' => $videoUrl,
                'is_active' => (bool) ($item['is_active'] ?? true),
            ];
        }

        return array_values(array_filter(
            $merged,
            fn ($item) => $item['is_active'] && (!empty($item['embed_url']) || !empty($item['video_url']))
        ));
    }
}
