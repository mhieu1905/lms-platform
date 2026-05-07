<?php
return [
    'pagination' => [
        'per_page' => 10,
    ],

    'popular_courses' => [
        'limits' => [
            'home.news-details' => 3,
        ],
        'default' => 5,
    ],

    'all_courses' => [
        'per_page' => 24,
    ],

    'course_details' => [
        'categories' => 6,
        'you_may_like' => 6,
        'latest_courses' => 4,
    ],

    'status' => [
        'public' => 1,
        'hidden' => 0,
    ],

    'currency' => [
        'usd_to_vnd' => 26000,
    ],

    'sepay' => [
        'api_key' => env('SEPAY_API_KEY'),
        'acc' => env('SEPAY_ACC', 'VQRQADYSP2633'),
        'bank' => env('SEPAY_BANK', 'MBBank'),
        'template' => env('SEPAY_TEMPLATE', 'compact'),
        'url_img_qr' => 'https://qr.sepay.vn/img?acc=:acc&bank=:bank&amount=:amount&des=:des&template=:template',
        'url_base' => 'https://my.sepay.vn/userapi'
    ],

    'paypal' => [
        'mode' => env('PAYPAL_MODE',),
        'client_id' => env('PAYPAL_SANDBOX_CLIENT_ID',),
        'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET',),
        'oauth_url' => 'https://api-m.sandbox.paypal.com/v1/oauth2/token',
        'order_url' => 'https://api-m.sandbox.paypal.com/v2/checkout/orders',
    ],

    'payment_qr' => [
        'expires_at' => 10,
    ],
    
    'courses_home' => [
        'per_page' => 8,
    ],

    'news_home' => [
        'per_page' => 7,
    ],
];
