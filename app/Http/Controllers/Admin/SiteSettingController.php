<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SiteSettingController extends Controller
{
    public function edit()
    {
        return view('dashboard.settings.site-settings', [
            'settings' => $this->settings(),
        ]);
    }

    public function update(Request $request)
    {
        $current = $this->settings();

        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:500',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'about' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'footer_text' => 'nullable|string',
            'logo_path' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'footer_logo_path' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'home_banner_image_1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'home_banner_image_2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'home_banner_image_3' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'home_banner_image_4' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        foreach ([
            'site_name' => $current->site_name ?: 'Bharat Biomer',
            'tagline' => $current->tagline ?: 'Advanced biological solutions for sustainable farming.',
            'email' => $current->email ?: 'admin@bharatbiomer.com',
            'phone' => $current->phone ?: '+91 7828333334',
        ] as $key => $fallback) {
            if (!isset($validated[$key]) || trim((string) $validated[$key]) === '') {
                $validated[$key] = $fallback;
            }
        }

        $this->upsertSettings($request, $validated);

        return redirect()->route('dashboard.site-settings.edit')
            ->with('success', 'Site settings updated successfully.');
    }

    public function editHomepage()
    {
        return view('dashboard.settings.homepage-editor', [
            'settings' => $this->settings(),
            'homepageConfig' => HomepageSetting::currentMerged(),
        ]);
    }

    public function updateHomepage(Request $request)
    {
        $validated = $request->validate([
            'hero_enabled' => 'nullable|boolean',
            'hero_slider_enabled' => 'nullable|boolean',
            'hero_slides' => 'nullable|array|max:4',
            'hero_slides.*.headline_prefix' => 'nullable|string|max:255',
            'hero_slides.*.headline_highlight' => 'nullable|string|max:255',
            'hero_slides.*.headline_suffix' => 'nullable|string|max:255',
            'hero_slides.*.description' => 'nullable|string|max:1000',
            'hero_slides.*.secondary_description' => 'nullable|string|max:1000',
            'hero_slides.*.primary_button_text' => 'nullable|string|max:100',
            'hero_slides.*.primary_button_url' => 'nullable|string|max:500',
            'hero_slides.*.secondary_button_text' => 'nullable|string|max:100',
            'hero_slides.*.secondary_button_url' => 'nullable|string|max:500',
            'hero_slides.*.media_type' => 'nullable|in:image,video',
            'hero_slides.*.media_url' => 'nullable|string|max:1000',
            'hero_slides.*.is_active' => 'nullable|boolean',
            'hero_slide_media_files' => 'nullable|array|max:4',
            'hero_slide_media_files.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'why_bharat_title' => 'nullable|string|max:255',
            'why_bharat_items' => 'nullable|array|max:4',
            'why_bharat_items.*.title' => 'nullable|string|max:255',
            'why_bharat_items.*.description' => 'nullable|string|max:500',
            'what_we_do_title' => 'nullable|string|max:255',
            'what_we_do_description' => 'nullable|string|max:2000',
            'what_we_do_label' => 'nullable|string|max:255',
            'what_we_do_image_url' => 'nullable|string|max:1000',
            'what_we_do_items' => 'nullable|array|max:4',
            'what_we_do_items.*.label' => 'nullable|string|max:255',
            'who_we_serve_title' => 'nullable|string|max:255',
            'who_we_serve_items' => 'nullable|array|max:5',
            'who_we_serve_items.*.label' => 'nullable|string|max:255',
            'key_highlights_title' => 'nullable|string|max:255',
            'key_highlights_subtitle' => 'nullable|string|max:255',
            'key_highlights_items' => 'nullable|array|max:4',
            'key_highlights_items.*.title' => 'nullable|string|max:255',
            'key_highlights_items.*.description' => 'nullable|string|max:500',
            'video_reviews_enabled' => 'nullable|boolean',
            'video_reviews_title' => 'nullable|string|max:255',
            'video_reviews_subtitle' => 'nullable|string|max:1000',
            'video_reviews_items' => 'nullable|array|max:6',
            'video_reviews_items.*.title' => 'nullable|string|max:255',
            'video_reviews_items.*.instagram_url' => 'nullable|string|max:1000',
            'video_reviews_items.*.is_active' => 'nullable|boolean',
            'video_review_files' => 'nullable|array|max:6',
            'video_review_files.*' => 'nullable|file|mimes:mp4,webm,ogg|max:51200',
        ]);

        $homepagePayload = [
            'hero_enabled' => $request->boolean('hero_enabled'),
            'hero_slider_enabled' => $request->boolean('hero_slider_enabled'),
            'hero_slides' => $this->prepareHeroSlides($validated['hero_slides'] ?? [], $request),
            'why_bharat_title' => $validated['why_bharat_title'] ?? null,
            'why_bharat_items' => $this->cleanRows($validated['why_bharat_items'] ?? [], ['title', 'description']),
            'what_we_do_title' => $validated['what_we_do_title'] ?? null,
            'what_we_do_description' => $validated['what_we_do_description'] ?? null,
            'what_we_do_label' => $validated['what_we_do_label'] ?? null,
            'what_we_do_image_url' => $validated['what_we_do_image_url'] ?? null,
            'what_we_do_items' => $this->cleanRows($validated['what_we_do_items'] ?? [], ['label']),
            'who_we_serve_title' => $validated['who_we_serve_title'] ?? null,
            'who_we_serve_items' => $this->cleanRows($validated['who_we_serve_items'] ?? [], ['label']),
            'key_highlights_title' => $validated['key_highlights_title'] ?? null,
            'key_highlights_subtitle' => $validated['key_highlights_subtitle'] ?? null,
            'key_highlights_items' => $this->cleanRows($validated['key_highlights_items'] ?? [], ['title', 'description']),
            'video_reviews_enabled' => $request->boolean('video_reviews_enabled'),
            'video_reviews_title' => $validated['video_reviews_title'] ?? null,
            'video_reviews_subtitle' => $validated['video_reviews_subtitle'] ?? null,
            'video_reviews_items' => $this->prepareVideoReviews($validated['video_reviews_items'] ?? [], $request),
        ];

        $homepage = HomepageSetting::first();
        if ($homepage) {
            $homepage->update($homepagePayload);
        } else {
            HomepageSetting::create($homepagePayload);
        }

        return redirect()->route('dashboard.homepage-editor.edit')
            ->with('success', 'Homepage editor updated successfully.');
    }

    public function editAnalytics()
    {
        return view('dashboard.settings.google-analytics', [
            'settings' => $this->settings(),
        ]);
    }

    public function updateAnalytics(Request $request)
    {
        $validated = $request->validate([
            'google_analytics_id' => ['nullable', 'string', 'max:50', 'regex:/^G-[A-Z0-9]+$/'],
        ]);

        $settings = SiteSetting::first();
        if ($settings) {
            $settings->update($validated);
        } else {
            SiteSetting::create($validated);
        }

        return redirect()->route('dashboard.google-analytics.edit')
            ->with('success', 'Google Analytics settings updated successfully.');
    }

    protected function prepareHeroSlides(array $slides, Request $request): array
    {
        $prepared = [];

        foreach (array_slice($slides, 0, 4) as $index => $slide) {
            $mediaUrl = trim((string) ($slide['media_url'] ?? ''));

            if ($request->hasFile("hero_slide_media_files.$index")) {
                $mediaUrl = $request->file("hero_slide_media_files.$index")->store('hero-slides', 'public');
            }

            $prepared[] = [
                'headline_prefix' => trim((string) ($slide['headline_prefix'] ?? '')),
                'headline_highlight' => trim((string) ($slide['headline_highlight'] ?? '')),
                'headline_suffix' => trim((string) ($slide['headline_suffix'] ?? '')),
                'description' => trim((string) ($slide['description'] ?? '')),
                'secondary_description' => trim((string) ($slide['secondary_description'] ?? '')),
                'primary_button_text' => trim((string) ($slide['primary_button_text'] ?? '')),
                'primary_button_url' => trim((string) ($slide['primary_button_url'] ?? '')),
                'secondary_button_text' => trim((string) ($slide['secondary_button_text'] ?? '')),
                'secondary_button_url' => trim((string) ($slide['secondary_button_url'] ?? '')),
                'media_type' => in_array(($slide['media_type'] ?? 'image'), ['image', 'video'], true) ? $slide['media_type'] : 'image',
                'media_url' => $mediaUrl,
                'is_active' => !empty($slide['is_active']),
            ];
        }

        return $prepared;
    }

    protected function prepareVideoReviews(array $rows, Request $request): array
    {
        $prepared = [];

        foreach (array_slice($rows, 0, 6) as $index => $row) {
            $instagramUrl = trim((string) ($row['instagram_url'] ?? ''));

            if ($request->hasFile("video_review_files.$index")) {
                $instagramUrl = $request->file("video_review_files.$index")->store('video-reviews', 'public');
            }

            if ($instagramUrl === '') {
                continue;
            }

            $prepared[] = [
                'title' => trim((string) ($row['title'] ?? 'Video Review')),
                'instagram_url' => $instagramUrl,
                'is_active' => !empty($row['is_active']),
            ];
        }

        return $prepared;
    }

    protected function cleanRows(array $rows, array $keys): array
    {
        $clean = [];

        foreach ($rows as $row) {
            $item = [];
            foreach ($keys as $key) {
                $item[$key] = trim((string) ($row[$key] ?? ''));
            }
            $clean[] = $item;
        }

        return $clean;
    }

    protected function settings(): SiteSetting
    {
        return SiteSetting::query()->orderByDesc('id')->first() ?? new SiteSetting();
    }

    protected function upsertSettings(Request $request, array $validated): SiteSetting
    {
        if ($request->hasFile('logo_path')) {
            $validated['logo_path'] = $request->file('logo_path')->store('logos', 'public');
        }

        if ($request->hasFile('footer_logo_path')) {
            $validated['footer_logo_path'] = $request->file('footer_logo_path')->store('logos', 'public');
        }

        foreach (['home_banner_image_1', 'home_banner_image_2', 'home_banner_image_3', 'home_banner_image_4'] as $bannerField) {
            if ($request->hasFile($bannerField)) {
                $validated[$bannerField] = $request->file($bannerField)->store('banners', 'public');
            }
        }

        // Avoid SQL errors when new settings fields are added in code but DB migration is not run yet.
        $existingColumns = Schema::getColumnListing('site_settings');
        $validated = array_intersect_key($validated, array_flip($existingColumns));

        $settings = SiteSetting::query()->orderByDesc('id')->first();
        if ($settings) {
            $settings->update($validated);
            return $settings->fresh();
        }

        return SiteSetting::create($validated);
    }
}
