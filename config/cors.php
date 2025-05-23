<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines which domains are allowed to access your
    | application's HTTP assets since Laravel can handle requests from
    | another domain.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'register'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'], // این خط رو تغییر بده به ['*']

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];