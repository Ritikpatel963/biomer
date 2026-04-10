<?php

namespace App\Http\Controllers;

use App\Models\OrderReturn;
use Illuminate\Http\Request;

class AdminOrderReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = OrderReturn::with(['order', 'customer', 'orderItem.product'])
            ->latest('requested_at')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('order', function ($orderQuery) use ($search) {
                    $orderQuery->where('order_number', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })->orWhereHas('customer', function ($customerQuery) use ($search) {
                    $customerQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('return_reason', 'like', "%{$search}%");
            });
        }

        $returns = $query->paginate(20)->withQueryString();

        $statusCounts = [
            'all' => OrderReturn::count(),
            'pending' => OrderReturn::where('status', 'pending')->count(),
            'approved' => OrderReturn::where('status', 'approved')->count(),
            'rejected' => OrderReturn::where('status', 'rejected')->count(),
            'refunded' => OrderReturn::where('status', 'refunded')->count(),
        ];

        return view('dashboard.order-returns.index', compact('returns', 'statusCounts'));
    }

    public function show($id)
    {
        $return = OrderReturn::with(['order.customer', 'customer', 'orderItem.product'])
            ->findOrFail($id);

        return view('dashboard.order-returns.show', compact('return'));
    }

    public function update(Request $request, $id)
    {
        $return = OrderReturn::with(['orderItem'])->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,refunded',
            'admin_notes' => 'nullable|string|max:1000',
            'refund_amount' => 'nullable|numeric|min:0',
            'return_tracking_number' => 'nullable|string|max:255',
        ]);

        $refundMax = (float) ($return->orderItem->subtotal ?? $return->order?->total_amount ?? 0);
        if (array_key_exists('refund_amount', $validated) && $validated['refund_amount'] !== null && (float) $validated['refund_amount'] > $refundMax) {
            return back()
                ->withErrors(['refund_amount' => 'Refund amount cannot be greater than the item subtotal.'])
                ->withInput();
        }

        $oldStatus = $return->status;
        $newStatus = $validated['status'];

        $payload = [
            'status' => $newStatus,
            'admin_notes' => $validated['admin_notes'] ?? null,
            'refund_amount' => $validated['refund_amount'] ?? null,
            'return_tracking_number' => $validated['return_tracking_number'] ?? null,
        ];

        if ($newStatus === 'approved' && !$return->approved_at) {
            $payload['approved_at'] = now();
        }

        if ($newStatus === 'refunded' && !$return->refunded_at) {
            $payload['refunded_at'] = now();
        }

        if ($newStatus !== 'approved' && $oldStatus !== 'approved') {
            $payload['approved_at'] = $return->approved_at;
        }

        if ($newStatus !== 'refunded' && $oldStatus !== 'refunded') {
            $payload['refunded_at'] = $return->refunded_at;
        }

        $return->update($payload);

        return redirect()
            ->route('dashboard.returns.show', $return->id)
            ->with('success', 'Return request updated successfully.');
    }
}
