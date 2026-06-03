<?php
return [
    'email' => env('SHIPROCKET_EMAIL'),
    'password' => env('SHIPROCKET_PASSWORD'),
    'pickup_location' => env('SHIPROCKET_PICKUP_LOCATION', 'Primary'),
    'base_url' => env('SHIPROCKET_BASE_URL', 'https://apiv2.shiprocket.in/v1/external'),
];
