<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\GlobalProductAttribute;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // ── Index ──────────────────────────────────────────────────────────────
    public function index()
    {
        $products = Product::with(['brand', 'category', 'variations'])
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->latest()
            ->paginate(15);

        return view('dashboard.products.index', compact('products'));
    }

    // ── Create form ────────────────────────────────────────────────────────
    public function create()
    {
        $brands     = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $tags       = Tag::orderBy('name')->get();
        $globalAttributes = GlobalProductAttribute::active()->orderBy('sort_order')->orderBy('name')->get();

        return view('dashboard.products.create', compact('brands', 'categories', 'tags', 'globalAttributes'));
    }

    // ── Store ──────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'sku'               => 'nullable|string|max:100|unique:products,sku',
            'brand_id'          => 'nullable|exists:brands,id',
            'category_id'       => 'nullable|exists:categories,id',
            'technical_content' => 'nullable|string|max:255',
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string',
            'base_price'        => 'required|numeric|min:0',
            'unit'              => 'required|string|max:50',
            'manage_stock'      => 'nullable|boolean',
            'stock_quantity'    => 'nullable|integer|min:0',
            'shipping_charge'   => 'nullable|numeric|min:0',
            'tax_rate'          => 'nullable|numeric|min:0|max:100',
            'status'            => 'required|in:active,inactive,draft',
            'video_url'         => 'nullable|url|max:500',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:500',
            'meta_keyword'      => 'nullable|string|max:500',
            'featured_image'    => 'nullable|image|max:2048',
            'gallery.*'         => 'nullable|image|max:2048',
            'tags'              => 'nullable|array',
            'tags.*'            => 'string',
        ]);

        $variations = $this->normalizedVariationPayloads($request);

        if ($variations->isNotEmpty()) {
            $varRules = [];
            foreach ($variations as $i => $v) {
                $varRules["normalized_variations.{$i}.attribute_name"]  = 'required|string|max:100';
                $varRules["normalized_variations.{$i}.attribute_value"] = 'required|string|max:255';
                $varRules["normalized_variations.{$i}.sku"]             = 'required|string|max:100|distinct|unique:product_variations,sku';
                $varRules["normalized_variations.{$i}.price"]           = 'required|numeric|min:0';
                $varRules["normalized_variations.{$i}.weight"]          = 'nullable|numeric|min:0';
                $varRules["normalized_variations.{$i}.unit"]            = 'nullable|string|max:50';
                $varRules["normalized_variations.{$i}.stock_quantity"]  = 'required|integer|min:0';
            }
            validator(['normalized_variations' => $variations->toArray()], $varRules)->validate();
        }

        // ── try/catch exposes any silent error ─────────────────────────────
        try {
            DB::transaction(function () use ($request, $variations) {

                // 1. Featured image
                $featuredImagePath = null;
                if ($request->hasFile('featured_image')) {
                    $featuredImagePath = $request->file('featured_image')
                        ->store('products/featured', 'public');
                }

                // 2. Create product
                $product = Product::create([
                    'name'              => $request->name,
                    'slug'              => Str::slug($request->name),
                    'sku'               => $request->filled('sku') ? $request->sku : null,
                    'brand_id'          => $request->brand_id,
                    'category_id'       => $request->category_id,
                    'technical_content' => $request->technical_content,
                    'description'       => $request->description,
                    'short_description' => $request->short_description,
                    'base_price'        => $request->base_price,
                    'unit'              => $request->unit ?? 'kg',
                    'manage_stock'      => $request->boolean('manage_stock'),
                    'stock_quantity'    => (int) $request->input('stock_quantity', 0),
                    'shipping_charge'   => $request->shipping_charge ?? 0,
                    'tax_rate'          => $request->tax_rate ?? 0,
                    'status'            => $request->status,
                    'featured_image'    => $featuredImagePath,
                    'video_url'         => $request->video_url,
                    'meta_title'        => $request->meta_title,
                    'meta_description'  => $request->meta_description,
                    'meta_keyword'      => $request->meta_keyword,
                ]);

                // 3. Gallery images
                if ($request->hasFile('gallery')) {
                    $order = 0;
                    foreach ($request->file('gallery') as $file) {
                        $path = $file->store('products/gallery', 'public');
                        ProductImage::create([
                            'product_id'  => $product->id,
                            'image_path'  => $path,
                            'sort_order'  => $order,
                            'is_featured' => $order === 0,
                        ]);
                        $order++;
                    }
                }

                // 4. Variations
                if ($variations->isNotEmpty()) {
                    $varFiles = $request->file('variations', []);

                    foreach ($variations as $index => $varData) {
                        $varImagePath = null;
                        if (
                            isset($varFiles[$index]['image']) &&
                            $varFiles[$index]['image']->isValid()
                        ) {
                            $varImagePath = $varFiles[$index]['image']
                                ->store('products/variations', 'public');
                        }

                        ProductVariation::create([
                            'product_id'      => $product->id,
                            'sku'             => $varData['sku'],
                            'attribute_name'  => $varData['attribute_name'],
                            'attribute_value' => $varData['attribute_value'],
                            'attributes'      => $varData['attributes'],
                            'price'           => $varData['price'],
                            'compare_at_price'=> $varData['compare_at_price'],
                            'cost_price'      => $varData['cost_price'],
                            'weight'          => $varData['weight'] ?? null,
                            'unit'            => $varData['unit'] ?? null,
                            'stock_quantity'  => (int) $varData['stock_quantity'],
                            'track_stock'     => $varData['track_stock'],
                            'is_in_stock'     => $varData['is_in_stock'],
                            'is_active'       => $varData['is_active'],
                            'is_default'      => $varData['is_default'],
                            'image_path'      => $varImagePath,
                        ]);
                    }

                    $this->syncProductAttributesFromVariations($product);
                }

                // 5. Tags
                if ($request->filled('tags') && is_array($request->tags)) {
                    $tagIds = [];
                    foreach ($request->tags as $tagName) {
                        $tagName = trim($tagName);
                        if (!$tagName) continue;
                        $tag      = Tag::firstOrCreate(
                            ['slug' => Str::slug($tagName)],
                            ['name' => $tagName]
                        );
                        $tagIds[] = $tag->id;
                    }
                    if (!empty($tagIds)) {
                        $product->tags()->sync($tagIds);
                    }
                }
            });

        } catch (\Exception $e) {
            Log::error('Product creation failed', [
                'message'   => $e->getMessage(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'trace'     => $e->getTraceAsString(),
                'request'   => $request->except(['featured_image', 'gallery', 'variations.*.image']),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to create product. Please try again.');
        }

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product created successfully!');
    }

    // ── Show ───────────────────────────────────────────────────────────────
    public function show(Product $product)
    {
        $product->load(['brand', 'category', 'tags', 'images', 'variations', 'attributes']);
        return view('dashboard.products.show', compact('product'));
    }

    // ── Edit form ──────────────────────────────────────────────────────────
    public function edit(Product $product)
    {
        $product->load(['brand', 'category', 'tags', 'images', 'variations', 'attributes']);
        $brands     = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $tags       = Tag::orderBy('name')->get();
        $globalAttributes = GlobalProductAttribute::active()->orderBy('sort_order')->orderBy('name')->get();

        return view('dashboard.products.create', compact('product', 'brands', 'categories', 'tags', 'globalAttributes'));
    }

    // ── Update ─────────────────────────────────────────────────────────────
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'sku'               => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'brand_id'          => 'nullable|exists:brands,id',
            'category_id'       => 'nullable|exists:categories,id',
            'technical_content' => 'nullable|string|max:255',
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string',
            'base_price'        => 'required|numeric|min:0',
            'unit'              => 'required|string|max:50',
            'manage_stock'      => 'nullable|boolean',
            'stock_quantity'    => 'nullable|integer|min:0',
            'shipping_charge'   => 'nullable|numeric|min:0',
            'tax_rate'          => 'nullable|numeric|min:0|max:100',
            'status'            => 'required|in:active,inactive,draft',
            'video_url'         => 'nullable|url|max:500',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:500',
            'meta_keyword'      => 'nullable|string|max:500',
            'featured_image'    => 'nullable|image|max:2048',
            'gallery.*'         => 'nullable|image|max:2048',
            'tags'              => 'nullable|array',
            'tags.*'            => 'string',
        ]);

        $variations = $this->normalizedVariationPayloads($request, $product);

        if ($variations->isNotEmpty()) {
            $varRules = [];
            foreach ($variations as $i => $v) {
                $variationId = $v['id'] ?? null;

                $varRules["normalized_variations.{$i}.id"] = [
                    'nullable',
                    Rule::exists('product_variations', 'id')->where('product_id', $product->id),
                ];
                $varRules["normalized_variations.{$i}.attribute_name"] = 'required|string|max:100';
                $varRules["normalized_variations.{$i}.attribute_value"] = 'required|string|max:255';
                $varRules["normalized_variations.{$i}.sku"] = [
                    'required',
                    'string',
                    'max:100',
                    'distinct',
                    Rule::unique('product_variations', 'sku')->ignore($variationId),
                ];
                $varRules["normalized_variations.{$i}.price"] = 'required|numeric|min:0';
                $varRules["normalized_variations.{$i}.weight"] = 'nullable|numeric|min:0';
                $varRules["normalized_variations.{$i}.unit"] = 'nullable|string|max:50';
                $varRules["normalized_variations.{$i}.stock_quantity"] = 'required|integer|min:0';
            }
            validator(['normalized_variations' => $variations->toArray()], $varRules)->validate();
        }

        try {
            DB::transaction(function () use ($request, $product, $variations) {

                // 1. Featured image
                $featuredImagePath = $product->featured_image;
                if ($request->hasFile('featured_image')) {
                    if ($product->featured_image) {
                        Storage::disk('public')->delete($product->featured_image);
                    }
                    $featuredImagePath = $request->file('featured_image')
                        ->store('products/featured', 'public');
                }

                // 2. Update product
                $product->update([
                    'name'              => $request->name,
                    'slug'              => Str::slug($request->name),
                    'sku'               => $request->filled('sku') ? $request->sku : null,
                    'brand_id'          => $request->brand_id,
                    'category_id'       => $request->category_id,
                    'technical_content' => $request->technical_content,
                    'description'       => $request->description,
                    'short_description' => $request->short_description,
                    'base_price'        => $request->base_price,
                    'unit'              => $request->unit ?? 'kg',
                    'manage_stock'      => $request->boolean('manage_stock'),
                    'stock_quantity'    => (int) $request->input('stock_quantity', 0),
                    'shipping_charge'   => $request->shipping_charge ?? 0,
                    'tax_rate'          => $request->tax_rate ?? 0,
                    'status'            => $request->status,
                    'featured_image'    => $featuredImagePath,
                    'video_url'         => $request->video_url,
                    'meta_title'        => $request->meta_title,
                    'meta_description'  => $request->meta_description,
                    'meta_keyword'      => $request->meta_keyword,
                ]);

                // 3. Gallery images
                if ($request->hasFile('gallery')) {
                    $lastOrder = $product->images()->max('sort_order') ?? -1;
                    foreach ($request->file('gallery') as $file) {
                        $path = $file->store('products/gallery', 'public');
                        ProductImage::create([
                            'product_id'  => $product->id,
                            'image_path'  => $path,
                            'sort_order'  => ++$lastOrder,
                            'is_featured' => false,
                        ]);
                    }
                }

                // 4. Variations — UPDATE existing, CREATE new
                if ($variations->isNotEmpty()) {
                    $varFiles = $request->file('variations', []);

                    foreach ($variations as $index => $varData) {
                        $varImagePath = null;
                        if (
                            isset($varFiles[$index]['image']) &&
                            $varFiles[$index]['image']->isValid()
                        ) {
                            $varImagePath = $varFiles[$index]['image']
                                ->store('products/variations', 'public');
                        }

                        if (!empty($varData['id'])) {
                            // UPDATE existing variation
                            $existing = ProductVariation::find($varData['id']);
                            if ($existing) {
                                if ($varImagePath && $existing->image_path) {
                                    Storage::disk('public')->delete($existing->image_path);
                                }
                                $existing->update([
                                    'sku'             => $varData['sku'],
                                    'attribute_name'  => $varData['attribute_name'],
                                    'attribute_value' => $varData['attribute_value'],
                                    'attributes'      => $varData['attributes'],
                                    'price'           => $varData['price'],
                                    'compare_at_price'=> $varData['compare_at_price'],
                                    'cost_price'      => $varData['cost_price'],
                                    'weight'          => $varData['weight'] ?? null,
                                    'unit'            => $varData['unit'] ?? null,
                                    'stock_quantity'  => (int) $varData['stock_quantity'],
                                    'track_stock'     => $varData['track_stock'],
                                    'is_in_stock'     => $varData['is_in_stock'],
                                    'is_active'       => $varData['is_active'],
                                    'is_default'      => $varData['is_default'],
                                    'image_path'      => $varImagePath ?: $existing->image_path,
                                ]);
                            }
                        } else {
                            // CREATE new variation
                            ProductVariation::create([
                                'product_id'      => $product->id,
                                'sku'             => $varData['sku'],
                                'attribute_name'  => $varData['attribute_name'],
                                'attribute_value' => $varData['attribute_value'],
                                'attributes'      => $varData['attributes'],
                                'price'           => $varData['price'],
                                'compare_at_price'=> $varData['compare_at_price'],
                                'cost_price'      => $varData['cost_price'],
                                'weight'          => $varData['weight'] ?? null,
                                'unit'            => $varData['unit'] ?? null,
                                'stock_quantity'  => (int) $varData['stock_quantity'],
                                'track_stock'     => $varData['track_stock'],
                                'is_in_stock'     => $varData['is_in_stock'],
                                'is_active'       => $varData['is_active'],
                                'is_default'      => $varData['is_default'],
                                'image_path'      => $varImagePath,
                            ]);
                        }
                    }

                    $this->syncProductAttributesFromVariations($product);
                }

                // 5. Tags sync
                if ($request->filled('tags') && is_array($request->tags)) {
                    $tagIds = [];
                    foreach ($request->tags as $tagName) {
                        $tagName = trim($tagName);
                        if (!$tagName) continue;
                        $tag      = Tag::firstOrCreate(
                            ['slug' => Str::slug($tagName)],
                            ['name' => $tagName]
                        );
                        $tagIds[] = $tag->id;
                    }
                    $product->tags()->sync($tagIds);
                } else {
                    $product->tags()->detach();
                }
            });

        } catch (\Exception $e) {
            Log::error('Product update failed', [
                'message'   => $e->getMessage(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'trace'     => $e->getTraceAsString(),
                'product_id'=> $product->id,
                'request'   => $request->except(['featured_image', 'gallery', 'variations.*.image']),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to update product. Please try again.');
        }

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product updated successfully!');
    }

    // ── Destroy ────────────────────────────────────────────────────────────
    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img->image_path);
            }
            if ($product->featured_image) {
                Storage::disk('public')->delete($product->featured_image);
            }
            $product->delete();
        });

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product deleted.');
    }

    // ── Delete single gallery image (AJAX) ────────────────────────────────
    public function destroyImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return response()->json(['success' => true]);
    }

    public function destroyFeaturedImage(Product $product)
    {
        if ($product->featured_image) {
            Storage::disk('public')->delete($product->featured_image);
            $product->forceFill(['featured_image' => null])->save();
        }

        return response()->json(['success' => true]);
    }

    // ── Delete single variation (AJAX) ────────────────────────────────────
    public function destroyVariation(ProductVariation $variation)
    {
        if ($variation->image_path) {
            Storage::disk('public')->delete($variation->image_path);
        }
        $variation->delete();
        return response()->json(['success' => true]);
    }

    // ── Frontend: Product Listing ──────────────────────────────────────────
public function shopIndex(Request $request)
{
    $query = Product::with(['brand', 'category', 'variations'])
        ->withAvg('approvedReviews', 'rating')
        ->withCount('approvedReviews')
        ->where('status', 'active');

    // Search functionality
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('short_description', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Category filter
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    // Brand filter
    if ($request->filled('brand')) {
        $query->where('brand_id', $request->brand);
    }

    // Price range filter
    if ($request->filled('min_price') || $request->filled('max_price')) {
        $query->where(function ($q) use ($request) {
            $q->where(function ($subQ) use ($request) {
                // Products with variations - check variation prices
                $subQ->whereHas('variations', function ($vq) use ($request) {
                    if ($request->filled('min_price')) {
                        $vq->where('price', '>=', $request->min_price);
                    }
                    if ($request->filled('max_price')) {
                        $vq->where('price', '<=', $request->max_price);
                    }
                });
            })->orWhere(function ($subQ) use ($request) {
                // Products without variations - check base price
                $subQ->whereDoesntHave('variations')
                     ->where(function ($priceQ) use ($request) {
                         if ($request->filled('min_price')) {
                             $priceQ->where('base_price', '>=', $request->min_price);
                         }
                         if ($request->filled('max_price')) {
                             $priceQ->where('base_price', '<=', $request->max_price);
                         }
                     });
            });
        });
    }

    // Sorting
    $sortBy = $request->get('sort', 'latest');
    switch ($sortBy) {
        case 'price_low':
            $query->orderByRaw('(SELECT MIN(price) FROM product_variations WHERE product_variations.product_id = products.id) ASC, base_price ASC');
            break;
        case 'price_high':
            $query->orderByRaw('(SELECT MAX(price) FROM product_variations WHERE product_variations.product_id = products.id) DESC, base_price DESC');
            break;
        case 'name':
            $query->orderBy('name');
            break;
        case 'latest':
        default:
            $query->latest();
            break;
    }

    $products = $query->paginate(12)->withQueryString();

    // Get filter options
    $categories = \App\Models\Category::orderBy('name')->get();
    $brands = \App\Models\Brand::orderBy('name')->get();

    return view('product', compact('products', 'categories', 'brands'));
}

// ── Frontend: Product Detail ───────────────────────────────────────────
// ── Frontend: Product Detail ───────────────────────────────────────────
public function shopShow(Product $product)
{
    abort_if($product->status !== 'active', 404);

    $product->load([
        'brand',
        'category',
        'tags',
        'images',
        'variations' => function ($q) {
            $q->where('is_active', true)->orderByDesc('is_default')->orderBy('price');
        },
        'reviews' => function ($q) {
            $q->with('customer')->where('status', 'approved')->latest();
        }
    ]);

    return view('productdetails', compact('product'));
}

    private function syncProductAttributesFromVariations(Product $product): void
    {
        $product->load('variations');

        $grouped = collect();

        foreach ($product->variations as $variation) {
            $attributes = $variation->attributes ?: [$variation->attribute_name => $variation->attribute_value];

            foreach ($attributes as $name => $value) {
                if (!filled($name) || !filled($value)) {
                    continue;
                }

                $grouped->push([
                    'name' => $name,
                    'value' => $value,
                ]);
            }
        }

        foreach ($grouped->groupBy('name') as $name => $items) {
            ProductAttribute::updateOrCreate(
                ['product_id' => $product->id, 'name' => $name],
                [
                    'values' => $items
                        ->pluck('value')
                        ->filter()
                        ->unique(fn ($value) => Str::lower($value))
                        ->values()
                        ->all(),
                ]
            );
        }
    }

    private function normalizedVariationPayloads(Request $request, ?Product $product = null)
    {
        $defaultRow = $request->input('default_variation_row');

        return collect($request->input('variations', []))
            ->map(function (array $variation, int $index) use ($defaultRow, $product, $request) {
                $attributes = collect($variation['attributes'] ?? [])
                    ->mapWithKeys(fn ($value, $name) => [trim((string) $name) => trim((string) $value)])
                    ->filter(fn ($value, $name) => filled($name) && filled($value))
                    ->all();

                if (empty($attributes)) {
                    $attributeName = trim((string) ($variation['attribute_name'] ?? 'Pack'));
                    $attributeValue = trim((string) ($variation['attribute_value'] ?? $variation['name'] ?? ''));
                    $attributes = filled($attributeName) && filled($attributeValue)
                        ? [$attributeName => $attributeValue]
                        : [];
                }

                $attributeName = array_key_first($attributes) ?: trim((string) ($variation['attribute_name'] ?? 'Variation'));
                $attributeValue = filled($variation['attribute_value'] ?? null)
                    ? trim((string) $variation['attribute_value'])
                    : $this->variationDisplayName($attributes, $variation['name'] ?? '');

                return [
                    'id' => $variation['id'] ?? null,
                    'name' => $this->variationDisplayName($attributes, $variation['name'] ?? ''),
                    'attribute_name' => $attributeName ?: 'Variation',
                    'attribute_value' => $attributeValue ?: 'Default',
                    'attributes' => $attributes,
                    'sku' => trim((string) ($variation['sku'] ?? $this->generatedVariationSku($product?->sku ?? $request->input('sku'), $attributes, $index))),
                    'price' => $variation['price'] ?? $request->input('base_price', 0),
                    'compare_at_price' => $variation['compare_at_price'] ?? null,
                    'cost_price' => $variation['cost_price'] ?? null,
                    'weight' => $variation['weight'] ?? null,
                    'unit' => $variation['unit'] ?? $request->input('unit'),
                    'stock_quantity' => $variation['stock_quantity'] ?? $variation['stock_qty'] ?? 0,
                    'track_stock' => (bool) ($variation['track_stock'] ?? true),
                    'is_in_stock' => (bool) ($variation['is_in_stock'] ?? true),
                    'is_active' => (bool) ($variation['is_active'] ?? false),
                    'is_default' => (string) $defaultRow === (string) $index || (bool) ($variation['is_default'] ?? false),
                ];
            })
            ->filter(fn ($variation) => filled($variation['sku']) && filled($variation['price']))
            ->values();
    }

    private function variationDisplayName(array $attributes, string $fallback = ''): string
    {
        if (!empty($attributes)) {
            return collect($attributes)
                ->map(fn ($value, $name) => "{$name}: {$value}")
                ->implode(' / ');
        }

        return trim($fallback);
    }

    private function generatedVariationSku(?string $baseSku, array $attributes, int $index): string
    {
        $parts = collect($attributes)->values()->push($index + 1)->implode('-');

        return Str::upper(Str::slug(($baseSku ?: 'PRODUCT') . '-' . $parts));
    }
}
