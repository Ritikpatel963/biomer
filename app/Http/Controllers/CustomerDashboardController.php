<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        $orderCounts = $customer->orders()
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw("SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as completed_orders")
            ->selectRaw("SUM(CASE WHEN status IN ('pending', 'confirmed', 'processing', 'shipped') THEN 1 ELSE 0 END) as pending_orders")
            ->selectRaw("SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders")
            ->first();

        $totalOrders     = (int) ($orderCounts->total_orders ?? 0);
        $completedOrders = (int) ($orderCounts->completed_orders ?? 0);
        $pendingOrders   = (int) ($orderCounts->pending_orders ?? 0);
        $cancelledOrders = (int) ($orderCounts->cancelled_orders ?? 0);
        $totalWishlist   = $customer->wishlists()->count();
        $recentOrders    = $customer->orders()->latest()->limit(5)->get();

        return view('dashboard', compact(
            'customer',
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'cancelledOrders',
            'totalWishlist',
            'recentOrders'
        ));
    }

    public function account()
    {
        $customer = Auth::guard('customer')->user();
        return view('my-account', compact('customer'));
    }

    public function edit()
    {
        $customer = Auth::guard('customer')->user();
        return view('customer-account-edit', compact('customer'));
    }

    public function update(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
        ];

        $changingPassword = $request->filled('current_password')
            || $request->filled('new_password')
            || $request->filled('new_password_confirmation');

        if ($changingPassword) {
            $rules['current_password'] = [
                'required',
                function ($attribute, $value, $fail) use ($customer) {
                    if (!Hash::check($value, $customer->password)) {
                        $fail('Current password is incorrect.');
                    }
                },
            ];
            $rules['new_password'] = [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-zA-Z])(?=.*[0-9])/',
            ];
            $rules['new_password_confirmation'] = 'required';
        }

        $validated = $request->validate($rules, [
            'new_password.regex' => 'New password must contain at least one letter and one number.',
        ]);

        if (isset($validated['new_password'])) {
            $validated['password'] = Hash::make($validated['new_password']);
        }

        unset($validated['current_password'], $validated['new_password'], $validated['new_password_confirmation']);

        $customer->update($validated);

        return redirect()->route('customer.account')->with('success', 'Account details updated successfully!');
    }
}
