<?php

return [
    'lastfm' => [
        'key' => env('LASTFM_API_KEY'),
        'base_url' => env('LASTFM_BASE_URL', 'https://ws.audioscrobbler.com/2.0/'),
    ],
];
