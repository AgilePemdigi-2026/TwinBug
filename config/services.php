<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'ml' => [
        'url' => env('ML_SERVICE_URL'),
        'key' => env('ML_API_KEY'),
        'timeout' => (int) env('ML_SERVICE_TIMEOUT', 60),
        'top_k' => (int) env('ML_SERVICE_TOP_K', 5),
        'max_candidates' => (int) env('ML_SERVICE_MAX_CANDIDATES', 500),
        'model' => env('ML_SERVICE_MODEL', 'paraphrase-multilingual-MiniLM-L12-v2'),
    ],

    'vision' => [
        'url' => env('VISION_SERVICE_URL', 'https://api.openai.com/v1/chat/completions'),
        'key' => env('VISION_SERVICE_KEY'),
        'model' => env('VISION_SERVICE_MODEL', 'gpt-4o-mini'),
        'timeout' => (int) env('VISION_SERVICE_TIMEOUT', 60),
    ],

];
