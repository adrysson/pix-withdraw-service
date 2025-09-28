<?php

use function Hyperf\Support\env;

return [
    'default' => env('MAIL_MAILER', 'mailer'),
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'no-reply@meusistema.local'),
    ],
    'mailers' => [
        'mailer' => [
            'host' => env('MAILER_HOST', 'mailhog'),
            'port' => env('MAILER_PORT', 1025),
        ]
    ],
];
