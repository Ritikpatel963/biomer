<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function company()
    {
        return view('settings/company');
    }
    
    public function currencies()
    {
        return view('settings/currencies');
    }
    
    public function language()
    {
        return view('settings/language');
    }
    
    public function notification()
    {
        return view('settings/notification');
    }
    
    public function notificationAlert()
    {
        return view('settings/notificationAlert');
    }
    
    public function paymentGateway()
    {
        $gateways = \App\Models\PaymentGateway::all();
        return view('settings/paymentGateway', compact('gateways'));
    }

    /**
     * Update payment gateway settings
     */
    public function updatePaymentGateway(Request $request)
    {
        $request->validate([
            'razorpay_enabled' => 'boolean',
            'razorpay_environment' => 'in:sandbox,production',
            
            'cashfree_enabled' => 'boolean',
            'cashfree_environment' => 'in:sandbox,production',
            
            'cod_enabled' => 'boolean',
        ]);

        // Update Razorpay
        \App\Models\PaymentGateway::updateOrCreate(
            ['gateway_name' => 'razorpay'],
            [
                'display_name' => 'Razorpay',
                'is_enabled' => $request->boolean('razorpay_enabled'),
                'environment' => $request->input('razorpay_environment'),
            ]
        );

        // Update Cashfree
        \App\Models\PaymentGateway::updateOrCreate(
            ['gateway_name' => 'cashfree'],
            [
                'display_name' => 'Cashfree',
                'is_enabled' => $request->boolean('cashfree_enabled'),
                'environment' => $request->input('cashfree_environment'),
            ]
        );

        // Update COD
        \App\Models\PaymentGateway::updateOrCreate(
            ['gateway_name' => 'cod'],
            [
                'display_name' => 'Cash on Delivery',
                'is_enabled' => $request->boolean('cod_enabled'),
            ]
        );

        return redirect()->back()->with('success', 'Payment gateway settings updated successfully!');
    }

    public function updatePaymentGatewayStatus(Request $request)
    {
        $data = $request->validate([
            'gateway_name' => 'required|in:razorpay,cashfree,cod',
            'is_enabled' => 'required|boolean',
        ]);

        $displayNames = [
            'razorpay' => 'Razorpay',
            'cashfree' => 'Cashfree',
            'cod' => 'Cash on Delivery',
        ];

        $gateway = \App\Models\PaymentGateway::firstOrCreate(
            ['gateway_name' => $data['gateway_name']],
            [
                'display_name' => $displayNames[$data['gateway_name']],
                'environment' => $data['gateway_name'] === 'cod' ? 'production' : 'sandbox',
            ]
        );

        $gateway->update([
            'display_name' => $displayNames[$data['gateway_name']],
            'is_enabled' => $request->boolean('is_enabled'),
        ]);

        return response()->json([
            'success' => true,
            'gateway_name' => $gateway->gateway_name,
            'is_enabled' => $gateway->is_enabled,
            'message' => $gateway->display_name . ' has been ' . ($gateway->is_enabled ? 'enabled' : 'disabled') . '.',
        ]);
    }
    
    public function theme()
    {
        return view('settings/theme');
    }
    
}
