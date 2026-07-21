<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePageSetting extends Model
{
    protected $fillable = [
        'problem_heading',
        'problem_paragraph',
        'problem_items',
        'solution_heading',
        'solution_paragraph',
        'solution_items',
        'why_heading',
        'why_paragraph',
        'why_items',
        'stats_background_image',
        'stats_items',
        'story_heading',
        'story_paragraph',
        'story_button_text',
        'story_button_url',
        'story_items',
    ];

    protected $casts = [
        'problem_items' => 'array',
        'solution_items' => 'array',
        'why_items' => 'array',
        'stats_items' => 'array',
        'story_items' => 'array',
    ];

    public static function defaults(): array
    {
        return [
            'problem_heading' => 'Farming today needs more than fertilizers',
            'problem_paragraph' => 'Modern farmers face multiple crop and soil challenges every season.',
            'problem_items' => [
                [
                    'image_path' => 'assets/bharat-biomer/plant.png',
                    'heading' => 'Soil degradation',
                    'paragraph' => 'Continuous farming reduces soil organic matter and health.',
                ],
                [
                    'image_path' => 'assets/bharat-biomer/root.png',
                    'heading' => 'Weak root development',
                    'paragraph' => 'Poor root growth limits nutrient and water absorption.',
                ],
                [
                    'image_path' => 'assets/bharat-biomer/npk.png',
                    'heading' => 'Nutrient inefficiency',
                    'paragraph' => 'Nutrients are not utilized effectively by the crop.',
                ],
                [
                    'image_path' => 'assets/bharat-biomer/pest.png',
                    'heading' => 'Pest and stress impact',
                    'paragraph' => 'Pests, diseases and climate stress reduce crop potential.',
                ],
                [
                    'image_path' => 'assets/bharat-biomer/plant-1.png',
                    'heading' => 'Lower yield and quality',
                    'paragraph' => 'All these factors lead to low productivity and poor produce quality.',
                ],
            ],
            'solution_heading' => 'Solutions by category',
            'solution_paragraph' => 'Clear, crop-focused biological inputs that support healthier growth and better yield outcomes.',
            'solution_items' => [
                [
                    'image_path' => 'assets/bharat-biomer/farmer3-card.webp',
                    'icon_path' => 'assets/bharat-biomer/science.png',
                    'heading' => 'Bio-Stimulants',
                    'paragraph' => 'Enhance plant growth, boost immunity and improve yield naturally.',
                    'url' => '#',
                ],
                [
                    'image_path' => 'assets/bharat-biomer/farmerland-card.webp',
                    'icon_path' => 'assets/bharat-biomer/research.png',
                    'heading' => 'Microbial Solutions',
                    'paragraph' => 'Beneficial microbes that improve soil health and nutrient availability.',
                    'url' => '#',
                ],
                [
                    'image_path' => 'assets/bharat-biomer/farmer1-card.webp',
                    'icon_path' => 'assets/bharat-biomer/plant.png',
                    'heading' => 'Crop Nutrition Support',
                    'paragraph' => 'Optimized nutrition for stronger growth and better productivity.',
                    'url' => '#',
                ],
                [
                    'image_path' => 'assets/bharat-biomer/footerfarmer-card.webp',
                    'icon_path' => 'assets/bharat-biomer/plant-1.png',
                    'heading' => 'Residue-free Farming',
                    'paragraph' => 'Safe, natural solutions for clean produce and sustainable farming.',
                    'url' => '#',
                ],
            ],
            'why_heading' => 'Why Bharat Biomer',
            'why_paragraph' => 'Built for performance, crop relevance and long-term soil health.',
            'why_items' => [
                [
                    'image_path' => 'assets/bharat-biomer/research.png',
                    'heading' => 'Research-driven formulations',
                    'paragraph' => 'Developed using advanced R&D and scientific innovation.',
                ],
                [
                    'image_path' => 'assets/bharat-biomer/shield.png',
                    'heading' => 'Field-tested performance',
                    'paragraph' => 'Proven results across diverse agro-climatic conditions.',
                ],
                [
                    'image_path' => 'assets/bharat-biomer/tour-guide.png',
                    'heading' => 'Crop-specific guidance',
                    'paragraph' => 'Expert recommendations tailored to each crop and stage.',
                ],
                [
                    'image_path' => 'assets/bharat-biomer/science.png',
                    'heading' => 'Sustainable biological approach',
                    'paragraph' => 'Eco-friendly solutions for long-term soil and environment health.',
                ],
                [
                    'image_path' => 'assets/bharat-biomer/heart-handshake.png',
                    'heading' => 'Reliable partner network',
                    'paragraph' => 'Strong distribution and support network across India.',
                ],
            ],
            'stats_background_image' => 'assets/bharat-biomer/farmerland.jpg',
            'stats_items' => [
                [
                    'icon_path' => 'assets/bharat-biomer/group.png',
                    'number' => '10,000+',
                    'heading' => 'Farmers Served',
                    'paragraph' => 'Across India',
                ],
                [
                    'icon_path' => 'assets/bharat-biomer/field.png',
                    'number' => '2,50,000+',
                    'heading' => 'Acres Impacted',
                    'paragraph' => 'And Growing',
                ],
                [
                    'icon_path' => 'assets/bharat-biomer/scientific-research.png',
                    'number' => '500+',
                    'heading' => 'Crop Trials',
                    'paragraph' => 'Successful Trials',
                ],
                [
                    'icon_path' => 'assets/bharat-biomer/helping-hand.png',
                    'number' => '150+',
                    'heading' => 'Channel Partners',
                    'paragraph' => 'Pan India Network',
                ],
            ],
            'story_heading' => 'Stories from the field',
            'story_paragraph' => 'Field outcomes, grower stories and dealer success journeys.',
            'story_button_text' => 'View More Stories',
            'story_button_url' => '#',
            'story_items' => [
                [
                    'thumbnail_path' => 'assets/bharat-biomer/farmer1.jpg',
                    'video_url' => '',
                    'duration' => '2:15',
                    'heading' => 'Tomato yield improvement',
                    'paragraph' => 'See how Bharat Biomer solutions increased yield and quality.',
                ],
                [
                    'thumbnail_path' => 'assets/bharat-biomer/farmer2.jpg',
                    'video_url' => '',
                    'duration' => '2:02',
                    'heading' => 'Healthy cotton growth',
                    'paragraph' => 'Stronger plants, more bolls and better profits for farmers.',
                ],
                [
                    'thumbnail_path' => 'assets/bharat-biomer/farmer3.jpg',
                    'video_url' => '',
                    'duration' => '1:48',
                    'heading' => 'Dealer success story',
                    'paragraph' => 'Our partner sharing growth journey with Bharat Biomer.',
                ],
                [
                    'thumbnail_path' => '',
                    'video_url' => '',
                    'duration' => '',
                    'heading' => '',
                    'paragraph' => '',
                ],
                [
                    'thumbnail_path' => '',
                    'video_url' => '',
                    'duration' => '',
                    'heading' => '',
                    'paragraph' => '',
                ],
                [
                    'thumbnail_path' => '',
                    'video_url' => '',
                    'duration' => '',
                    'heading' => '',
                    'paragraph' => '',
                ],
            ],
        ];
    }

    public static function currentMerged(): array
    {
        $defaults = static::defaults();
        $settings = static::query()->latest('id')->first();

        if (! $settings) {
            return $defaults;
        }

        return [
            'problem_heading' => $settings->problem_heading ?: $defaults['problem_heading'],
            'problem_paragraph' => $settings->problem_paragraph ?: $defaults['problem_paragraph'],
            'problem_items' => static::mergeProblemItems($settings->problem_items, $defaults['problem_items']),
            'solution_heading' => $settings->solution_heading ?: $defaults['solution_heading'],
            'solution_paragraph' => $settings->solution_paragraph ?: $defaults['solution_paragraph'],
            'solution_items' => static::mergeSolutionItems($settings->solution_items, $defaults['solution_items']),
            'why_heading' => $settings->why_heading ?: $defaults['why_heading'],
            'why_paragraph' => $settings->why_paragraph ?: $defaults['why_paragraph'],
            'why_items' => static::mergeBasicItems($settings->why_items, $defaults['why_items']),
            'stats_background_image' => $settings->stats_background_image ?: $defaults['stats_background_image'],
            'stats_items' => static::mergeStatsItems($settings->stats_items, $defaults['stats_items']),
            'story_heading' => $settings->story_heading ?: $defaults['story_heading'],
            'story_paragraph' => $settings->story_paragraph ?: $defaults['story_paragraph'],
            'story_button_text' => $settings->story_button_text ?: $defaults['story_button_text'],
            'story_button_url' => $settings->story_button_url ?: $defaults['story_button_url'],
            'story_items' => static::mergeStoryItems($settings->story_items, $defaults['story_items']),
        ];
    }

    public static function imageUrl(?string $path): string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return asset('assets/bharat-biomer/plant.png');
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $normalizedPath = ltrim($path, '/');

        if (str_starts_with($normalizedPath, 'storage/')) {
            return asset($normalizedPath);
        }

        if (str_starts_with($normalizedPath, 'assets/')) {
            return asset($normalizedPath);
        }

        return asset('storage/' . $normalizedPath);
    }

    public static function videoEmbedUrl(?string $url): string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return '';
        }

        if (preg_match('/src=["\']([^"\']+)["\']/i', $url, $matches)) {
            $url = $matches[1];
        }

        $parts = parse_url($url);

        if (! is_array($parts) || empty($parts['host'])) {
            return $url;
        }

        $host = strtolower($parts['host']);
        $path = trim($parts['path'] ?? '', '/');

        if (str_contains($host, 'youtube.com')) {
            if (str_starts_with($path, 'embed/')) {
                return 'https://www.youtube.com/embed/' . substr($path, 6);
            }

            if (str_starts_with($path, 'shorts/')) {
                return 'https://www.youtube.com/embed/' . substr($path, 7);
            }

            parse_str($parts['query'] ?? '', $query);

            if (! empty($query['v'])) {
                return 'https://www.youtube.com/embed/' . $query['v'];
            }
        }

        if (str_contains($host, 'youtu.be') && $path !== '') {
            return 'https://www.youtube.com/embed/' . $path;
        }

        if (str_contains($host, 'vimeo.com') && $path !== '') {
            return 'https://player.vimeo.com/video/' . $path;
        }

        return $url;
    }

    protected static function mergeProblemItems(?array $items, array $defaults): array
    {
        if ($items === null) {
            return $defaults;
        }

        $items = is_array($items) ? array_values($items) : [];
        $merged = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $merged[] = [
                'image_path' => trim((string) ($item['image_path'] ?? '')),
                'heading' => trim((string) ($item['heading'] ?? '')),
                'paragraph' => trim((string) ($item['paragraph'] ?? '')),
            ];
        }

        return $merged;
    }

    protected static function mergeSolutionItems(?array $items, array $defaults): array
    {
        if ($items === null) {
            return $defaults;
        }

        $items = is_array($items) ? array_values($items) : [];
        $merged = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $merged[] = [
                'image_path' => trim((string) ($item['image_path'] ?? '')),
                'icon_path' => trim((string) ($item['icon_path'] ?? '')),
                'heading' => trim((string) ($item['heading'] ?? '')),
                'paragraph' => trim((string) ($item['paragraph'] ?? '')),
                'url' => trim((string) ($item['url'] ?? '#')),
            ];
        }

        return $merged;
    }

    protected static function mergeBasicItems(?array $items, array $defaults): array
    {
        if ($items === null) {
            return $defaults;
        }

        $items = is_array($items) ? array_values($items) : [];
        $merged = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $merged[] = [
                'image_path' => trim((string) ($item['image_path'] ?? '')),
                'heading' => trim((string) ($item['heading'] ?? '')),
                'paragraph' => trim((string) ($item['paragraph'] ?? '')),
            ];
        }

        return $merged;
    }

    protected static function mergeStatsItems(?array $items, array $defaults): array
    {
        if ($items === null) {
            return $defaults;
        }

        $items = is_array($items) ? array_values($items) : [];
        $merged = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $merged[] = [
                'icon_path' => trim((string) ($item['icon_path'] ?? '')),
                'number' => trim((string) ($item['number'] ?? '')),
                'heading' => trim((string) ($item['heading'] ?? '')),
                'paragraph' => trim((string) ($item['paragraph'] ?? '')),
            ];
        }

        return $merged;
    }

    protected static function mergeStoryItems(?array $items, array $defaults): array
    {
        if ($items === null) {
            return $defaults;
        }

        $items = is_array($items) ? array_values($items) : [];
        $merged = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $merged[] = [
                'thumbnail_path' => trim((string) ($item['thumbnail_path'] ?? '')),
                'video_url' => trim((string) ($item['video_url'] ?? '')),
                'duration' => trim((string) ($item['duration'] ?? '')),
                'heading' => trim((string) ($item['heading'] ?? '')),
                'paragraph' => trim((string) ($item['paragraph'] ?? '')),
            ];
        }

        return $merged;
    }
}
