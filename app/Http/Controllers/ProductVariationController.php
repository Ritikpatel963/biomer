<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GlobalProductAttribute;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductVariationController extends Controller
{
    public function index(Product $product)
    {
        $product->load('attributes');
        $variations = $product->variations()->orderByDesc('is_default')->latest()->get();
        $globalAttributes = GlobalProductAttribute::active()->orderBy('sort_order')->orderBy('name')->get();
        return view('dashboard.variations.index', compact('product', 'variations', 'globalAttributes'));
    }

    public function create(Product $product)
    {
        $globalAttributes = GlobalProductAttribute::active()->orderBy('sort_order')->orderBy('name')->get();
        return view('dashboard.variations.create', compact('product', 'globalAttributes'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'sku'             => 'required|string|max:100|unique:product_variations,sku',
            'attribute_name'  => 'required|string|max:100',
            'attribute_value' => 'required|string|max:100',
            'price'           => 'required|numeric|min:0',
            'weight'          => 'nullable|numeric|min:0',
            'stock_quantity'  => 'required|integer|min:0',
            'is_default'      => 'nullable|boolean',
            'image'           => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products/variations', 'public');
        }

        if ($request->boolean('is_default')) {
            $product->variations()->update(['is_default' => false]);
        }

        $variation = $product->variations()->create([
            'sku'             => $request->sku,
            'attribute_name'  => $request->attribute_name,
            'attribute_value' => $request->attribute_value,
            'price'           => $request->price,
            'weight'          => $request->weight,
            'stock_quantity'  => $request->stock_quantity,
            'is_active'       => $request->boolean('is_active', true),
            'is_default'      => $request->boolean('is_default'),
            'image_path'      => $imagePath,
        ]);

        if (!$product->variations()->where('id', '!=', $variation->id)->where('is_default', true)->exists()) {
            $variation->update(['is_default' => true]);
        }

        return redirect()->route('dashboard.products.variations.index', $product)
            ->with('success', 'Variation added successfully!');
    }

    public function edit(Product $product, ProductVariation $variation)
    {
        $globalAttributes = GlobalProductAttribute::active()->orderBy('sort_order')->orderBy('name')->get();
        return view('dashboard.variations.edit', compact('product', 'variation', 'globalAttributes'));
    }

    public function update(Request $request, Product $product, ProductVariation $variation)
    {
        $request->validate([
            'sku'             => 'required|string|max:100|unique:product_variations,sku,' . $variation->id,
            'attribute_name'  => 'required|string|max:100',
            'attribute_value' => 'required|string|max:100',
            'price'           => 'required|numeric|min:0',
            'weight'          => 'nullable|numeric|min:0',
            'stock_quantity'  => 'required|integer|min:0',
            'is_default'      => 'nullable|boolean',
            'image'           => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($variation->image_path) Storage::disk('public')->delete($variation->image_path);
            $variation->image_path = $request->file('image')->store('products/variations', 'public');
        }

        if ($request->boolean('is_default')) {
            $product->variations()->whereKeyNot($variation->id)->update(['is_default' => false]);
        }

        $variation->update([
            'sku'             => $request->sku,
            'attribute_name'  => $request->attribute_name,
            'attribute_value' => $request->attribute_value,
            'price'           => $request->price,
            'weight'          => $request->weight,
            'stock_quantity'  => $request->stock_quantity,
            'is_active'       => $request->boolean('is_active', true),
            'is_default'      => $request->boolean('is_default'),
            'image_path'      => $variation->image_path,
        ]);

        return redirect()->route('dashboard.products.variations.index', $product)
            ->with('success', 'Variation updated!');
    }

    public function destroy(Product $product, ProductVariation $variation)
    {
        $wasDefault = $variation->is_default;
        if ($variation->image_path) Storage::disk('public')->delete($variation->image_path);
        $variation->delete();
        if ($wasDefault && $product->variations()->exists()) {
            $product->variations()->oldest()->first()?->update(['is_default' => true]);
        }
        return redirect()->route('dashboard.products.variations.index', $product)
            ->with('success', 'Variation deleted.');
    }

    public function storeAttribute(Request $request, Product $product)
    {
        $validated = $request->validate([
            'global_attribute_id' => 'required|exists:global_product_attributes,id',
            'selected_values' => 'nullable|array',
            'selected_values.*' => 'string|max:100',
            'custom_values' => 'nullable|string|max:1000',
            'default_value' => 'nullable|string|max:100',
            'base_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
        ]);

        $attribute = GlobalProductAttribute::active()->findOrFail($validated['global_attribute_id']);
        $customValues = collect(preg_split('/[\r\n,]+/', $validated['custom_values'] ?? ''))
            ->map(fn ($value) => trim($value))
            ->filter();
        $selectedValues = collect($validated['selected_values'] ?? [])
            ->map(fn ($value) => trim($value))
            ->filter();

        $values = $selectedValues
            ->merge($customValues)
            ->unique(fn ($value) => Str::lower($value))
            ->values();

        if ($values->isEmpty()) {
            $values = collect($attribute->values ?? [])
                ->map(fn ($value) => trim($value))
                ->filter()
                ->unique(fn ($value) => Str::lower($value))
                ->values();
        }

        if ($values->isEmpty()) {
            return back()->withErrors(['selected_values' => 'Add at least one attribute value.'])->withInput();
        }

        ProductAttribute::updateOrCreate(
            ['product_id' => $product->id, 'name' => $attribute->name],
            ['values' => $values->all()]
        );

        $basePrice = $validated['base_price'] ?? $product->base_price;
        $stock = $validated['stock_quantity'] ?? 0;
        $defaultValue = $validated['default_value'] ?? $values->first();

        foreach ($values as $value) {
            $exists = $product->variations()
                ->where('attribute_name', $attribute->name)
                ->where('attribute_value', $value)
                ->exists();

            if ($exists) {
                continue;
            }

            $isDefault = $value === $defaultValue && !$product->variations()->where('is_default', true)->exists();
            if ($value === $defaultValue) {
                $product->variations()->update(['is_default' => false]);
                $isDefault = true;
            }

            $baseSku = Str::upper(Str::slug($product->sku . '-' . $attribute->name . '-' . $value));
            $sku = $baseSku;
            $counter = 2;
            while (ProductVariation::where('sku', $sku)->exists()) {
                $sku = $baseSku . '-' . $counter++;
            }

            $product->variations()->create([
                'sku' => $sku,
                'attribute_name' => $attribute->name,
                'attribute_value' => $value,
                'price' => $basePrice,
                'weight' => null,
                'unit' => $product->unit,
                'stock_quantity' => $stock,
                'is_active' => true,
                'is_default' => $isDefault,
            ]);
        }

        return redirect()->route('dashboard.products.variations.index', $product)
            ->with('success', 'Variations generated from the global attribute.');
    }

    // AJAX – toggle active status
    public function toggleStatus(ProductVariation $variation)
    {
        $variation->update(['is_active' => !$variation->is_active]);
        return response()->json(['is_active' => $variation->is_active]);
    }
}
