<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Status Update</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2d7a45; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .footer { background: #333; color: white; padding: 20px; text-align: center; font-size: 12px; }
        .btn { display: inline-block; background: #2d7a45; color: white !important; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        .status-update { background: white; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #2d7a45; }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #cce5ff; color: #004085; }
        .status-shipped { background: #d1ecf1; color: #0c5460; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Status Update</h1>
            <p>Order #{{ $order->order_number }}</p>
        </div>

        <div class="content">
            <h2>Hello {{ $order->customer->name }},</h2>

            <div class="status-update">
                <h3>Order Status Changed</h3>
                <p>Your order status has been updated:</p>
                <p>
                    <strong>From:</strong> <span class="status-badge status-{{ strtolower($oldStatus) }}">{{ ucfirst($oldStatus) }}</span><br>
                    <strong>To:</strong> <span class="status-badge status-{{ strtolower($newStatus) }}">{{ ucfirst($newStatus) }}</span>
                </p>
                <p><strong>Updated on:</strong> {{ now()->format('d M Y, H:i') }}</p>
            </div>

            <p><strong>Order Details:</strong></p>
            <ul>
                <li><strong>Order Number:</strong> {{ $order->order_number }}</li>
                <li><strong>Order Date:</strong> {{ $order->created_at->format('d M Y') }}</li>
                <li><strong>Total Amount:</strong> ₹{{ number_format($order->total_amount, 2) }}</li>
            </ul>

            @if($newStatus === 'processing')
                <div style="background: #e8f5ed; padding: 15px; border-radius: 5px; margin: 20px 0;">
                    <h4>Order Processing Started</h4>
                    <p>Your order is now being processed. Our team will prepare your items for shipping.</p>
                </div>
            @elseif($newStatus === 'shipped')
                <div style="background: #cce5ff; padding: 15px; border-radius: 5px; margin: 20px 0;">
                    <h4>Order Shipped</h4>
                    <p>Your order has been shipped and is on its way to you. You will receive tracking information soon.</p>
                </div>
            @elseif($newStatus === 'delivered')
                <div style="background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0;">
                    <h4>Order Delivered</h4>
                    <p>Your order has been successfully delivered. Thank you for shopping with us!</p>
                    <p>We hope you love your products. Please consider leaving a review.</p>
                </div>
            @elseif($newStatus === 'cancelled')
                <div style="background: #f8d7da; padding: 15px; border-radius: 5px; margin: 20px 0;">
                    <h4>Order Cancelled</h4>
                    <p>Your order has been cancelled. If you have any questions, please contact our support team.</p>
                </div>
            @endif

            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ route('orders.show', $order->order_number) }}" class="btn">View Order Details</a>
            </p>

            <p>If you have any questions about your order, please contact our support team.</p>

            <p>Best regards,<br>
            <strong>Bharat Biomer Team</strong></p>
        </div>

        <div class="footer">
            <p>&copy; 2026 Bharat Biomer. All rights reserved.</p>
            <p>This email was sent to {{ $order->customer->email }}</p>
        </div>
    </div>
</body>
</html>