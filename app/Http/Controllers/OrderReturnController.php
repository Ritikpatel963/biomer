<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderReturnController extends Controller
{
    public function index()
    {
        $returns = OrderReturn::where('customer_id', Auth::guard('customer')->id())
            ->with(['order'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('order-returns', compact('returns'));
    }

    public function create($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('customer_id', Auth::guard('customer')->id())
            ->with(['items.product'])
            ->firstOrFail();

        // Only allow returns for delivered orders within 30 days
        if ($order->status !== 'delivered' || $order->delivered_at && $order->delivered_at->diffInDays(now()) > 30) {
            abort(403, 'Returns are only allowed for delivered orders within 30 days.');
        }

        return view('order-return-create', compact('order'));
    }

    public function store(Request $request, $orderNumber)
    {
        $request->merge([
            'description' => trim((string) $request->input('description', '')),
        ]);

        $order = Order::where('order_number', $orderNumber)
            ->where('customer_id', Auth::guard('customer')->id())
            ->with(['items'])
            ->firstOrFail();

        // Validate return request
        if ($order->status !== 'delivered' || $order->delivered_at && $order->delivered_at->diffInDays(now()) > 30) {
            return back()->withErrors(['error' => 'Returns are only allowed for delivered orders within 30 days.']);
        }

        $orderItemIds = $order->items->pluck('id')->all();

        $validator = Validator::make($request->all(), [
            'order_item_id' => [
                'required',
                'integer',
                Rule::exists('order_items', 'id'),
                Rule::in($orderItemIds),
            ],
            'reason' => 'required|string|in:defective,wrong_item,not_as_described,damaged,other',
            'description' => 'required|string|min:10|max:500',
            'refund_amount' => 'required|numeric|min:0',
        ], [
            'description.min' => 'Please describe the issue in at least 10 characters.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $selectedItem = $order->items->firstWhere('id', (int) $validated['order_item_id']);
        if (!$selectedItem) {
            return back()->withErrors(['order_item_id' => 'Please select a valid order item.'])->withInput();
        }

        $refundMax = min(
            (float) $selectedItem->subtotal,
            (float) $order->net_amount
        );

        if ((float) $validated['refund_amount'] > $refundMax) {
            return back()
                ->withErrors(['refund_amount' => 'Refund amount cannot be greater than the refundable paid amount for this item.'])
                ->withInput();
        }

        // Check if return already exists for this order item
        $existingReturn = OrderReturn::where('order_id', $order->id)
            ->where('order_item_id', $selectedItem->id)
            ->first();
        if ($existingReturn) {
            return back()->withErrors(['error' => 'A return request already exists for the selected item.']);
        }

        OrderReturn::create([
            'order_id' => $order->id,
            'order_item_id' => $selectedItem->id,
            'customer_id' => Auth::guard('customer')->id(),
            'reason' => $validated['reason'],
            'description' => $validated['description'],
            'status' => 'pending',
            'refund_amount' => $validated['refund_amount'],
            'requested_at' => now(),
        ]);

        return redirect()->route('order-returns.index')->with('success', 'Return request submitted successfully. We will review it shortly.');
    }

    public function show($id)
    {
        $return = OrderReturn::where('id', $id)
            ->where('customer_id', Auth::guard('customer')->id())
            ->with(['order.items.product'])
            ->firstOrFail();

        return view('order-return-show', compact('return'));
    }
}
