<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',                    // custom (fortify routes)
        'logout',                   // custom (fortify routes)
        'register',                 // custom (fortify routes)
        'forgot-password',           // custom (fortify routes)
        'reset-password',           // custom (fortify routes)
        'reset-password/*',           // custom (fortify routes)
        'two-factor-challenge',         // custom (fortify routes)
        'user',                       // custom (fortify routes)
        'user/*',                       // custom (fortify routes)
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*', 'http://localhost:8080', 'http://localhost:8081'], // 'http://localhost:3000' [env('CLIENT_APP_URL')], ['http://localhost:8080'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // false,

];
