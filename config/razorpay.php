<?php
return [
	'key_id' => env('RAZORPAY_KEY_ID'),
	'key_secret' => env('RAZORPAY_KEY_SECRET'),
	'environment' => env('RAZORPAY_ENV', 'sandbox'),
	'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET'),
];
