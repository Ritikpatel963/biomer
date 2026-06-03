<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Failed</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc3545; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .footer { background: #333; color: white; padding: 20px; text-align: center; font-size: 12px; }
        .btn { display: inline-block; background: #2d7a45; color: white !important; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Payment Failed</h1>
            <p>Order #{{ $order->order_number }}</p>
        </div>

        <div class="content">
            <h2>Hello {{ $order->customer->name }},</h2>

            <div class="warning">
                <strong>Payment Unsuccessful</strong><br>
                We were unable to process your payment for Order #{{ $order->order_number }}.
            </div>

            <p><strong>Order Details:</strong></p>
            <ul>
                <li><strong>Order Number:</strong> {{ $order->order_number }}</li>
                <li><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, H:i') }}</li>
                <li><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</li>
                <li><strong>Amount:</strong> ₹{{ number_format($order->total_amount, 2) }}</li>
            </ul>

            @if($reason)
            <p><strong>Reason for Failure:</strong> {{ $reason }}</p>
            @endif

            <p><strong>What you can do:</strong></p>
            <ol>
                <li>Check your payment method details and try again</li>
                <li>Ensure sufficient balance/funds are available</li>
                <li>Contact your bank/card issuer if the issue persists</li>
                <li>Try a different payment method</li>
            </ol>

            <p>Your cart items have been saved. You can retry the payment from your cart or order history.</p>

            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ route('cart.index') }}" class="btn">Retry Payment</a>
            </p>

            <p>If you continue to face issues, please contact our support team with your order number.</p>

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