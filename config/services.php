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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // config/services.php — Ajouter la section youtube

    'youtube' => [
        'channel_id'   => env('YOUTUBE_CHANNEL_ID', ''),
        'channel_name' => env('YOUTUBE_CHANNEL_NAME', 'TradiPratic'),
        'channel_url'  => env('YOUTUBE_CHANNEL_URL', 'https://www.youtube.com/@TradiPratic'),
        'subscribe_url'=> env('YOUTUBE_SUBSCRIBE_URL', 'https://www.youtube.com/@TradiPratic?sub_confirmation=1'),
    ],

];
