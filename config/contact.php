<?php

return [

    'phone' => env('PHONE_NUMBER'),
    'email' => env('MAIL_FROM_ADDRESS'),

    'whatsapp' => [
        'number' => env('WHATSAPP_NUMBER'),
        'message' => env('WHATSAPP_DEFAULT_MESSAGE', 'Bonjour'),
    ],

    'social' => [
        'facebook' => env('FACEBOOK_URL'),
        'tiktok'  => env('TIKTOK_URL'),
        'instagram' => env('INSTAGRAM_URL'),
        'youtube'  => env('YOUTUBE_URL'),
    ],
];
