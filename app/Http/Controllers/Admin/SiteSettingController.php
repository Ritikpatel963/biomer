<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function editAnalytics()
    {
        return view('dashboard.settings.google-analytics', [
            'settings' => $this->settings(),
        ]);
    }

    public function updateAnalytics(Request $request)
    {
        $validated = $request->validate([
            'google_analytics_id' => ['nullable', 'string', 'max:50', 'regex:/^(G|GTM)-[A-Z0-9]+$/'],
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

    protected function settings(): SiteSetting
    {
        return SiteSetting::current() ?? new SiteSetting();
    }

    protected function upsertSettings(Request $request, array $validated): SiteSetting
    {
        if ($request->hasFile('logo_path')) {
            $validated['logo_path'] = $request->file('logo_path')->store('logos', 'public');
        }

        if ($request->hasFile('footer_logo_path')) {
            $validated['footer_logo_path'] = $request->file('footer_logo_path')->store('logos', 'public');
        }

        // Avoid SQL errors when new settings fields are added in code but DB migration is not run yet.
        $existingColumns = Schema::getColumnListing('site_settings');
        $validated = array_intersect_key($validated, array_flip($existingColumns));

        $settings = SiteSetting::current();
        if ($settings) {
            $settings->update($validated);
            return $settings->fresh();
        }

        return SiteSetting::create($validated);
    }
}
