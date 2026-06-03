<?php
return [
    'app_id' => env('CASHFREE_APP_ID'),
    'secret_key' => env('CASHFREE_SECRET_KEY'),
    'environment' => env('CASHFREE_ENV', 'sandbox'),
    'base_url' => env('CASHFREE_ENV', 'sandbox') === 'production'
    ? 'https://api.cashfree.com/pg'
    : 'https://sandbox.cashfree.com/pg',
];
