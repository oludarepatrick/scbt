<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */
    /*'openrouter' => [
    'api_key' => env('OPENROUTER_API_KEY'),
    'referer' => env('APP_URL', 'https://schooldrive.com.ng'),
    ],*/

    'gemini' => [
    'api_key' => env('GEMINI_API_KEY'),
    ],

    'google' => [
        'credentials_path' => storage_path('app/keys/cbtgeminikey.json'),
        'project_id' => env('GOOGLE_PROJECT_ID'),
        'location' => env('GOOGLE_LOCATION', 'us-central1'),
        // You can add other Google-related config here in the future
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

];
