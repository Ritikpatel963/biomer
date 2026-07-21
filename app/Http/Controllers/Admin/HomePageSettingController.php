<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomePageSetting;
use Illuminate\Http\Request;

class HomePageSettingController extends Controller
{
    public function edit()
    {
        return view('dashboard.settings.home-page', [
            'homePageConfig' => HomePageSetting::currentMerged(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'problem_heading' => 'nullable|string|max:255',
            'problem_paragraph' => 'nullable|string|max:1000',
            'problem_items' => 'nullable|array|max:20',
            'problem_items.*.image_path' => 'nullable|string|max:1000',
            'problem_items.*.heading' => 'nullable|string|max:255',
            'problem_items.*.paragraph' => 'nullable|string|max:1000',
            'problem_item_images' => 'nullable|array|max:20',
            'problem_item_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'solution_heading' => 'nullable|string|max:255',
            'solution_paragraph' => 'nullable|string|max:1000',
            'solution_items' => 'nullable|array|max:20',
            'solution_items.*.image_path' => 'nullable|string|max:1000',
            'solution_items.*.icon_path' => 'nullable|string|max:1000',
            'solution_items.*.heading' => 'nullable|string|max:255',
            'solution_items.*.paragraph' => 'nullable|string|max:1000',
            'solution_items.*.url' => 'nullable|string|max:1000',
            'solution_item_images' => 'nullable|array|max:20',
            'solution_item_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'solution_item_icons' => 'nullable|array|max:20',
            'solution_item_icons.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'why_heading' => 'nullable|string|max:255',
            'why_paragraph' => 'nullable|string|max:1000',
            'why_items' => 'nullable|array|max:20',
            'why_items.*.image_path' => 'nullable|string|max:1000',
            'why_items.*.heading' => 'nullable|string|max:255',
            'why_items.*.paragraph' => 'nullable|string|max:1000',
            'why_item_images' => 'nullable|array|max:20',
            'why_item_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'stats_background_image' => 'nullable|string|max:1000',
            'stats_background_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'stats_items' => 'nullable|array|max:20',
            'stats_items.*.icon_path' => 'nullable|string|max:1000',
            'stats_items.*.number' => 'nullable|string|max:100',
            'stats_items.*.heading' => 'nullable|string|max:255',
            'stats_items.*.paragraph' => 'nullable|string|max:500',
            'stats_item_icons' => 'nullable|array|max:20',
            'stats_item_icons.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'story_heading' => 'nullable|string|max:255',
            'story_paragraph' => 'nullable|string|max:1000',
            'story_button_text' => 'nullable|string|max:100',
            'story_button_url' => 'nullable|string|max:1000',
            'story_items' => 'nullable|array|max:50',
            'story_items.*.thumbnail_path' => 'nullable|string|max:1000',
            'story_items.*.video_url' => 'nullable|string|max:2000',
            'story_items.*.duration' => 'nullable|string|max:50',
            'story_items.*.heading' => 'nullable|string|max:255',
            'story_items.*.paragraph' => 'nullable|string|max:1000',
            'story_item_thumbnails' => 'nullable|array|max:50',
            'story_item_thumbnails.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $problemItems = $validated['problem_items'] ?? [];

        foreach (range(0, 19) as $index) {
            if ($request->hasFile("problem_item_images.$index")) {
                $problemItems[$index]['image_path'] = $request->file("problem_item_images.$index")
                    ->store('home-page/problem-items', 'public');
            }
        }

        $solutionItems = $validated['solution_items'] ?? [];

        foreach (range(0, 19) as $index) {
            if ($request->hasFile("solution_item_images.$index")) {
                $solutionItems[$index]['image_path'] = $request->file("solution_item_images.$index")
                    ->store('home-page/solution-items', 'public');
            }

            if ($request->hasFile("solution_item_icons.$index")) {
                $solutionItems[$index]['icon_path'] = $request->file("solution_item_icons.$index")
                    ->store('home-page/solution-icons', 'public');
            }
        }

        $whyItems = $validated['why_items'] ?? [];

        foreach (range(0, 19) as $index) {
            if ($request->hasFile("why_item_images.$index")) {
                $whyItems[$index]['image_path'] = $request->file("why_item_images.$index")
                    ->store('home-page/why-items', 'public');
            }
        }

        $statsBackgroundImage = $validated['stats_background_image'] ?? null;

        if ($request->hasFile('stats_background_file')) {
            $statsBackgroundImage = $request->file('stats_background_file')
                ->store('home-page/stats', 'public');
        }

        $statsItems = $validated['stats_items'] ?? [];

        foreach (range(0, 19) as $index) {
            if ($request->hasFile("stats_item_icons.$index")) {
                $statsItems[$index]['icon_path'] = $request->file("stats_item_icons.$index")
                    ->store('home-page/stats-icons', 'public');
            }
        }

        $storyItems = $validated['story_items'] ?? [];

        foreach (range(0, 49) as $index) {
            if ($request->hasFile("story_item_thumbnails.$index")) {
                $storyItems[$index]['thumbnail_path'] = $request->file("story_item_thumbnails.$index")
                    ->store('home-page/story-thumbnails', 'public');
            }
        }

        $payload = [
            'problem_heading' => $validated['problem_heading'] ?? null,
            'problem_paragraph' => $validated['problem_paragraph'] ?? null,
            'problem_items' => $this->cleanProblemItems($problemItems),
            'solution_heading' => $validated['solution_heading'] ?? null,
            'solution_paragraph' => $validated['solution_paragraph'] ?? null,
            'solution_items' => $this->cleanSolutionItems($solutionItems),
            'why_heading' => $validated['why_heading'] ?? null,
            'why_paragraph' => $validated['why_paragraph'] ?? null,
            'why_items' => $this->cleanWhyItems($whyItems),
            'stats_background_image' => $statsBackgroundImage,
            'stats_items' => $this->cleanStatsItems($statsItems),
            'story_heading' => $validated['story_heading'] ?? null,
            'story_paragraph' => $validated['story_paragraph'] ?? null,
            'story_button_text' => $validated['story_button_text'] ?? null,
            'story_button_url' => $validated['story_button_url'] ?? null,
            'story_items' => $this->cleanStoryItems($storyItems),
        ];

        $settings = HomePageSetting::query()->latest('id')->first();

        if ($settings) {
            $settings->update($payload);
        } else {
            HomePageSetting::create($payload);
        }

        return redirect()
            ->route('dashboard.home-page.edit')
            ->with('success', 'Home page section updated successfully.');
    }

    protected function cleanProblemItems(array $items): array
    {
        $clean = [];

        foreach (array_values($items) as $item) {
            if (! is_array($item)) {
                continue;
            }

            $row = [
                'image_path' => trim((string) ($item['image_path'] ?? '')),
                'heading' => trim((string) ($item['heading'] ?? '')),
                'paragraph' => trim((string) ($item['paragraph'] ?? '')),
            ];

            if (implode('', $row) !== '') {
                $clean[] = $row;
            }
        }

        return $clean;
    }

    protected function cleanSolutionItems(array $items): array
    {
        $clean = [];

        foreach (array_values($items) as $item) {
            if (! is_array($item)) {
                continue;
            }

            $row = [
                'image_path' => trim((string) ($item['image_path'] ?? '')),
                'icon_path' => trim((string) ($item['icon_path'] ?? '')),
                'heading' => trim((string) ($item['heading'] ?? '')),
                'paragraph' => trim((string) ($item['paragraph'] ?? '')),
                'url' => trim((string) ($item['url'] ?? '#')),
            ];

            if (implode('', $row) !== '#') {
                $clean[] = $row;
            }
        }

        return $clean;
    }

    protected function cleanWhyItems(array $items): array
    {
        $clean = [];

        foreach (array_values($items) as $item) {
            if (! is_array($item)) {
                continue;
            }

            $row = [
                'image_path' => trim((string) ($item['image_path'] ?? '')),
                'heading' => trim((string) ($item['heading'] ?? '')),
                'paragraph' => trim((string) ($item['paragraph'] ?? '')),
            ];

            if (implode('', $row) !== '') {
                $clean[] = $row;
            }
        }

        return $clean;
    }

    protected function cleanStatsItems(array $items): array
    {
        $clean = [];

        foreach (array_values($items) as $item) {
            if (! is_array($item)) {
                continue;
            }

            $row = [
                'icon_path' => trim((string) ($item['icon_path'] ?? '')),
                'number' => trim((string) ($item['number'] ?? '')),
                'heading' => trim((string) ($item['heading'] ?? '')),
                'paragraph' => trim((string) ($item['paragraph'] ?? '')),
            ];

            if (implode('', $row) !== '') {
                $clean[] = $row;
            }
        }

        return $clean;
    }

    protected function cleanStoryItems(array $items): array
    {
        $clean = [];

        foreach (array_values($items) as $item) {
            if (! is_array($item)) {
                continue;
            }

            $row = [
                'thumbnail_path' => trim((string) ($item['thumbnail_path'] ?? '')),
                'video_url' => trim((string) ($item['video_url'] ?? '')),
                'duration' => trim((string) ($item['duration'] ?? '')),
                'heading' => trim((string) ($item['heading'] ?? '')),
                'paragraph' => trim((string) ($item['paragraph'] ?? '')),
            ];

            if (implode('', $row) !== '') {
                $clean[] = $row;
            }
        }

        return $clean;
    }
}
