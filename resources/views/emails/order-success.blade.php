<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2d7a45; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .footer { background: #333; color: white; padding: 20px; text-align: center; font-size: 12px; }
        .btn { display: inline-block; background: #2d7a45; color: white !important; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        .order-details { background: white; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .product-item { border-bottom: 1px solid #eee; padding: 10px 0; }
        .total { font-weight: bold; font-size: 18px; color: #2d7a45; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Confirmed!</h1>
        </div>

        <div class="content">
            <h2>Thank you for your order, {{ $order->customer->name }}!</h2>

            <p>Your order has been successfully placed and confirmed. Here are the details:</p>

            <div class="order-details">
                <h3>Order Summary</h3>
                <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>

                <h4>Items Ordered:</h4>
                @foreach($order->items as $item)
                <div class="product-item">
                    <strong>{{ $item->product->name }}</strong>
                    @if($item->variation)
                        <br><small>Variant: {{ $item->variation->attribute_value }}</small>
                    @endif
                    <br>Quantity: {{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}
                    <span style="float: right;">₹{{ number_format($item->total, 2) }}</span>
                </div>
                @endforeach

                <div style="border-top: 2px solid #2d7a45; margin-top: 15px; padding-top: 15px;">
                    <p class="total">Total: ₹{{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>

            <h3>Shipping Address</h3>
            <p>
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_pincode }}<br>
                Phone: {{ $order->shipping_phone }}
            </p>

            <p><strong>What happens next?</strong></p>
            <ul>
                <li>You will receive updates on your order status via email</li>
                <li>Our team will process your order within 1-2 business days</li>
                <li>You can track your order status in your account dashboard</li>
            </ul>

            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ route('orders.show', $order->order_number) }}" class="btn" style="color:white">View Order Details</a>
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