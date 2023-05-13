<?php

return [
    'store_url' => env('SHOPIFY_STORE_URL'),
    'api'       => [
        'version' => env('SHOPIFY_API_VERSION', '2022-04'),
        'key'     => env('SHOPIFY_ADMIN_API_KEY'),
        'token'   => env('SHOPIFY_ADMIN_API_TOKEN'),
        'secret'  => env('SHOPIFY_ADMIN_API_SECRET')
    ],
];