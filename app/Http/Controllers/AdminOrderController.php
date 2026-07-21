<?php

namespace App\Http\Controllers;

use App\Mail\OrderStatusUpdate;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminOrderController extends Controller
{
    // ── Index ──────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Order::with('customer')
            ->withCount('items')
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by order number or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        $statusCounts = array_merge([
            'all'        => 0,
            'pending'    => 0,
            'confirmed'  => 0,
            'processing' => 0,
            'shipped'    => 0,
            'delivered'  => 0,
            'cancelled'  => 0,
        ], Order::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->map(fn ($total) => (int) $total)
            ->toArray());

        $statusCounts['all'] = array_sum($statusCounts);

        return view('dashboard.orders.index', compact('orders', 'statusCounts'));
    }

    // ── Show ───────────────────────────────────────────────────────────
    public function show($orderNumber)
    {
        $order = Order::with(['items.product', 'customer'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('dashboard.orders.show', compact('order'));
    }

    // ── Update Status (AJAX) ───────────────────────────────────────────
    public function updateStatus(Request $request, $orderNumber)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        $oldStatus = $order->status;
        $newStatus = $request->status;

        $updateData = ['status' => $newStatus];

        if ($newStatus === 'delivered' && !$order->delivered_at) {
            $updateData['delivered_at'] = now();
        }

        $order->update($updateData);

        // Send status update email if status actually changed
        if ($oldStatus !== $newStatus) {
            try {
                Mail::to($order->email)->send(new OrderStatusUpdate($order, $oldStatus, $newStatus));
            } catch (\Exception $e) {
                Log::error('Failed to send order status update email: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'status'  => $order->status,
            'message' => 'Order status updated to ' . ucfirst($order->status),
        ]);
    }
}
