<?php

namespace App\Http\Controllers;

use App\Models\AudiencePreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AudiencePreferenceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'audience_type' => 'required|in:kisan,partners,dealers',
            'source_url' => 'nullable|string|max:500',
        ]);

        $customer = Auth::guard('customer')->user();
        $sessionToken = $request->session()->getId();

        AudiencePreference::updateOrCreate(
            ['session_token' => $sessionToken],
            [
                'customer_id' => $customer?->id,
                'audience_type' => $validated['audience_type'],
                'name' => $customer?->name,
                'email' => $customer?->email,
                'phone' => $customer?->phone,
                'source_url' => $validated['source_url'] ?? url()->previous(),
            ]
        );

        if ($customer) {
            $customer->update(['audience_type' => $validated['audience_type']]);
        }

        return response()->json(['success' => true]);
    }
}
