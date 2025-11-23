<?php
return [
    'env' => env('MPESA_ENV', 'sandbox'),
    'consumer_key' => env('MPESA_CONSUMER_KEY'),
    'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
    'shortcode' => env('MPESA_SHORTCODE'),
    'passkey' => env('MPESA_PASSKEY'),
    'callback_url' => env('MPESA_CALLBACK_URL'),
    'bearer_cache_key' => env('MPESA_BEARER_CACHE', 'mpesa_bearer_token'),
    // endpoints
    'endpoints' => [
        'sandbox' => [
            'oauth' => 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials',
            'stk_push' => 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest',
            'c2b_register' => 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl',
            'c2b_simulate' => 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate',
            'transaction_status' => 'https://sandbox.safaricom.co.ke/mpesa/transactionstatus/v1/query',
            'b2c' => 'https://sandbox.safaricom.co.ke/mpesa/b2c/v1/paymentrequest',
        ],
        'production' => [
            'oauth' => 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials',
            'stk_push' => 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest',
            'c2b_register' => 'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl',
            'c2b_simulate' => 'https://api.safaricom.co.ke/mpesa/c2b/v1/simulate',
            'transaction_status' => 'https://api.safaricom.co.ke/mpesa/transactionstatus/v1/query',
            'b2c' => 'https://api.safaricom.co.ke/mpesa/b2c/v1/paymentrequest',
        ],
    ],
];
