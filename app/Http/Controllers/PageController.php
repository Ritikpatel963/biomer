<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a single page by slug with SEO meta tags
     */
    public function show(Page $page)
    {
        // Only show published pages
        if (!$page->status) {
            abort(404);
        }

        return view('page.show', compact('page'));
    }
}
