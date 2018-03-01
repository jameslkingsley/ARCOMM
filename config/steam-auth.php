<?php

return [
    'redirect_url' => '/auth',
    'api_key' => env('STEAM_API_KEY', ''),
    'https' => env('STEAM_HTTPS', false),
    'group' => env('STEAM_GROUP', '')
];
