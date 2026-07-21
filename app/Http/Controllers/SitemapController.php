<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Blog;
use App\Models\Page;

class SitemapController extends Controller
{
    public function index()
    {
        return response()->view('sitemap.index')->header('Content-Type', 'text/xml');
    }

    public function pages()
    {
        $pages = Page::where('status', 1)->get();
        return response()->view('sitemap.pages', compact('pages'))->header('Content-Type', 'text/xml');
    }

    public function products()
    {
        $products = Product::where('status', 'active')->get();
        return response()->view('sitemap.products', compact('products'))->header('Content-Type', 'text/xml');
    }

    public function blogs()
    {
        $blogs = Blog::where('status', 'published')->orderByDesc('published_at')->get();
        return response()->view('sitemap.blogs', compact('blogs'))->header('Content-Type', 'text/xml');
    }
}
