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

        $statusCounts = [
            'all'        => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(),
            'confirmed'  => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped'    => Order::where('status', 'shipped')->count(),
            'delivered'  => Order::where('status', 'delivered')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

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

        $order->update(['status' => $newStatus]);

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