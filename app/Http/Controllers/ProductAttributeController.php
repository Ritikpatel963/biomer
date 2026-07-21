<?php

namespace App\Http\Controllers;

use App\Models\GlobalProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductAttributeController extends Controller
{
    public function index()
    {
        $attributes = GlobalProductAttribute::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15);

        return view('dashboard.attributes.index', compact('attributes'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateAttribute($request);
        $values = $this->parseValues($validated['values']);

        if (empty($values)) {
            return back()->withErrors(['values' => 'Add at least one attribute value.'])->withInput();
        }

        GlobalProductAttribute::create([
            'name' => trim($validated['name']),
            'slug' => Str::slug($validated['name']),
            'values' => $values,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('dashboard.attributes.index')
            ->with('success', 'Attribute created. It is now available for every product.');
    }

    public function update(Request $request, GlobalProductAttribute $attribute)
    {
        $validated = $this->validateAttribute($request, $attribute);
        $values = $this->parseValues($validated['values']);

        if (empty($values)) {
            return back()->withErrors(['values' => 'Add at least one attribute value.'])->withInput();
        }

        $attribute->update([
            'name' => trim($validated['name']),
            'slug' => Str::slug($validated['name']),
            'values' => $values,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('dashboard.attributes.index')
            ->with('success', 'Attribute updated.');
    }

    public function destroy(GlobalProductAttribute $attribute)
    {
        $attribute->delete();

        return redirect()->route('dashboard.attributes.index')
            ->with('success', 'Attribute deleted.');
    }

    private function validateAttribute(Request $request, ?GlobalProductAttribute $attribute = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('global_product_attributes', 'name')->ignore($attribute?->id),
            ],
            'values' => 'required|string|max:2000',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
    }

    private function parseValues(string $values): array
    {
        return collect(preg_split('/[\r\n,]+/', $values))
            ->map(fn ($value) => trim($value))
            ->filter()
            ->unique(fn ($value) => Str::lower($value))
            ->values()
            ->all();
    }
}
