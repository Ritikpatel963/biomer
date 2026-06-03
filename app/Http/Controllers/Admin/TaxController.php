<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('q', ''));

        $products = Product::with(['brand', 'category'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('tax_rate', 'like', "%{$search}%")
                        ->orWhere('gst_rate', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'total' => Product::count(),
            'with_tax' => Product::where(function ($q) {
                $q->where('tax_rate', '>', 0)
                    ->orWhere('gst_rate', '>', 0);
            })->count(),
            'without_tax' => Product::where('tax_rate', 0)->where('gst_rate', 0)->count(),
        ];

        return view('dashboard.taxes.index', compact('products', 'search', 'counts'));
    }
}
