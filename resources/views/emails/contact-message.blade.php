<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Message</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6;">
    <h2 style="color: #166534;">New Contact Form Message</h2>

    <p><strong>Name:</strong> {{ $submission['name'] }}</p>
    <p><strong>Email:</strong> {{ $submission['email'] }}</p>
    <p><strong>Phone:</strong> {{ $submission['phone'] }}</p>

    <p><strong>Message:</strong></p>
    <p style="white-space: pre-line;">{{ $submission['message'] }}</p>
</body>
</html>
