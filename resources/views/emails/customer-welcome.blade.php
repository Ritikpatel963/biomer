<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to Bharat Biomer</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2d7a45; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .footer { background: #333; color: white; padding: 20px; text-align: center; font-size: 12px; }
        .btn { display: inline-block; background: #2d7a45; color: white !important; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌱 Welcome to Bharat Biomer!</h1>
        </div>

        <div class="content">
            <h2>Hello {{ $customer->name }}!</h2>

            <p>Thank you for creating an account with Bharat Biomer. Your account has been successfully created and is ready to use.</p>

            <p><strong>Account Details:</strong></p>
            <ul>
                <li><strong>Name:</strong> {{ $customer->name }}</li>
                <li><strong>Email:</strong> {{ $customer->email }}</li>
                <li><strong>Registration Date:</strong> {{ $customer->created_at->format('d M Y') }}</li>
            </ul>

            <p>You can now:</p>
            <ul>
                <li>Browse our range of bio-stimulants and agri solutions</li>
                <li>Add products to your wishlist</li>
                <li>Place orders securely</li>
                <li>Track your order history</li>
                <li>Write product reviews</li>
            </ul>

            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/products') }}" class="btn">Start Shopping</a>
            </p>

            <p>If you have any questions, feel free to contact our support team.</p>

            <p>Best regards,<br>
            <strong>Bharat Biomer Team</strong></p>
        </div>

        <div class="footer">
            <p>&copy; 2026 Bharat Biomer. All rights reserved.</p>
            <p>This email was sent to {{ $customer->email }}</p>
        </div>
    </div>
</body>
</html>