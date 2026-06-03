<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SiteSetting;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'items'])
            ->latest()
            ->paginate(20);

        return view('dashboard.invoices.index', compact('orders'));
    }
    // ── Admin Download ─────────────────────────────────────────────────
    public function downloadAdmin($orderNumber)
    {
        $order = Order::with(['items.product', 'customer'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();
        $company = $this->invoiceCompany();

        $pdf = Pdf::loadView('Invoices.invoice', compact('order', 'company'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    // ── Customer Download ──────────────────────────────────────────────
    public function downloadCustomer($orderNumber)
    {
        $order = Order::with(['items.product', 'customer'])
            ->where('order_number', $orderNumber)
            ->where('customer_id', auth()->guard('customer')->id())
            ->firstOrFail();
        $company = $this->invoiceCompany();

        $pdf = Pdf::loadView('Invoices.invoice', compact('order', 'company'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('invoice-' . $order->order_number . '.pdf');
    }

    // ── Admin Invoice Management ───────────────────────────────────────
    public function invoiceAdd()
    {
        return redirect()
            ->route('dashboard.invoices.index')
            ->with('info', 'Invoices are generated automatically from orders.');
    }

    public function invoiceEdit($orderNumber = null)
    {
        if (!$orderNumber) {
            return redirect()
                ->route('dashboard.invoices.index')
                ->with('info', 'Select an order invoice to edit or review.');
        }

        return redirect()->route('dashboard.orders.show', $orderNumber);
    }

    public function invoiceList()
    {
        return redirect()->route('dashboard.invoices.index');
    }

    public function invoicePreview($orderNumber = null)
    {
        $query = Order::with(['customer', 'items.product'])->latest();
        $order = $orderNumber
            ? $query->where('order_number', $orderNumber)->firstOrFail()
            : $query->first();

        if (!$order) {
            return redirect()
                ->route('dashboard.invoices.index')
                ->with('info', 'No orders are available for invoice preview.');
        }

        $company = $this->invoiceCompany();

        return view('Invoices.invoicePreview', compact('order', 'company'));
    }

    private function invoiceCompany(): array
    {
        $settings = SiteSetting::first();
        $storageLogo = $settings?->logo_path ? public_path('storage/' . $settings->logo_path) : null;
        $publicLogo = public_path('assets/images/home-img/bb logo.png');
        $assetLogo = $settings?->logo_path ? asset('storage/' . $settings->logo_path) : asset('assets/images/home-img/bb logo.png');

        return [
            'name' => $settings?->site_name ?: 'Bharat Biomer',
            'tagline' => $settings?->tagline ?: 'Advanced biological solutions for sustainable farming.',
            'email' => $settings?->email ?: 'admin@bharatbiomer.com',
            'phone' => $settings?->phone ?: '+91 7828333334',
            'address' => $settings?->address ?: 'Company address not configured',
            'gstin' => 'Not configured',
            'logo_path' => $storageLogo && file_exists($storageLogo) ? $storageLogo : $publicLogo,
            'logo_url' => $assetLogo,
        ];
    }
}
