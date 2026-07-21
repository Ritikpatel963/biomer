<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('products')->latest()->paginate(20);
        return view('dashboard.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('dashboard.tags.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'name' => trim((string) $request->input('name', '')),
            'slug' => trim((string) $request->input('slug', '')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100', 'unique:tags,name'],
            'slug' => ['nullable', 'string', 'max:100', 'alpha_dash', 'unique:tags,slug'],
        ]);

        Tag::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
        ]);

        return redirect()->route('dashboard.tags.index')
            ->with('success', 'Tag created successfully!');
    }

    public function edit(Tag $tag)
    {
        return view('dashboard.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $request->merge([
            'name' => trim((string) $request->input('name', '')),
            'slug' => trim((string) $request->input('slug', '')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100', Rule::unique('tags', 'name')->ignore($tag->id)],
            'slug' => ['nullable', 'string', 'max:100', 'alpha_dash', Rule::unique('tags', 'slug')->ignore($tag->id)],
        ]);

        $tag->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
        ]);

        return redirect()->route('dashboard.tags.index')
            ->with('success', 'Tag updated successfully!');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('dashboard.tags.index')
            ->with('success', 'Tag deleted.');
    }

    // Bulk add tags (comma or newline separated)
    public function bulkStore(Request $request)
    {
        $request->validate(['bulk_tags' => 'required|string|max:3000']);

        $raw  = $request->bulk_tags;
        $names = preg_split('/[\n,]+/', $raw);
        $count = 0;
        $invalidNames = [];

        foreach ($names as $name) {
            $name = trim($name);
            if (!$name) continue;
            if (mb_strlen($name) > 100) {
                $invalidNames[] = $name;
                continue;
            }
            $slug = Str::slug($name);
            if ($slug === '') {
                $invalidNames[] = $name;
                continue;
            }
            Tag::firstOrCreate(['slug' => $slug], ['name' => $name]);
            $count++;
        }

        if ($count === 0) {
            return back()->withErrors(['bulk_tags' => 'Please enter at least one valid tag.'])->withInput();
        }

        if (!empty($invalidNames)) {
            return redirect()->route('dashboard.tags.index')
                ->with('success', "{$count} tag(s) added. Some invalid or too-long tags were skipped.");
        }

        return redirect()->route('dashboard.tags.index')
            ->with('success', "{$count} tag(s) added successfully!");
    }

    // AJAX: search tags for product form autocomplete
    public function search(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $query = trim((string) ($validated['q'] ?? ''));

        $tags = Tag::where('name', 'like', '%' . $query . '%')
            ->limit(10)
            ->get(['id', 'name', 'slug']);
        return response()->json($tags);
    }
}
