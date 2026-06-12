<?php

return [
    'subject' => env('VAPID_SUBJECT', 'mailto:contact@sandy-juice.com'),
    'vapid' => [
        'public_key'  => env('VAPID_PUBLIC_KEY'),
        'private_key' => env('VAPID_PRIVATE_KEY'),
    ],
];
