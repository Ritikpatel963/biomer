<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductFaq;
use Illuminate\Http\Request;

class ProductFaqController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'question'   => 'required|string|max:500',
            'answer'     => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $faq = $product->faqs()->create($data);

        if ($request->expectsJson()) {
            return response()->json(['faq' => $faq], 201);
        }

        return back()->with('success', 'FAQ added.');
    }

    public function update(Request $request, ProductFaq $faq)
    {
        $data = $request->validate([
            'question'   => 'required|string|max:500',
            'answer'     => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $faq->update($data);

        if ($request->expectsJson()) {
            return response()->json(['faq' => $faq]);
        }

        return back()->with('success', 'FAQ updated.');
    }

    public function destroy(ProductFaq $faq)
    {
        $faq->delete();

        if (request()->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'FAQ deleted.');
    }
}
