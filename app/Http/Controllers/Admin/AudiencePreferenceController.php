<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AudiencePreference;

class AudiencePreferenceController extends Controller
{
    public function index()
    {
        $preferences = AudiencePreference::with('customer')->latest()->paginate(20);

        $counts = [
            'all' => AudiencePreference::count(),
            'kisan' => AudiencePreference::where('audience_type', 'kisan')->count(),
            'partners' => AudiencePreference::where('audience_type', 'partners')->count(),
            'dealers' => AudiencePreference::where('audience_type', 'dealers')->count(),
        ];

        return view('dashboard.audience-preferences.index', compact('preferences', 'counts'));
    }
}
