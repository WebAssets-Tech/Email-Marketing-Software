<?php

return [
    /*
    |--------------------------------------------------------------------------
    | List your email providers
    |--------------------------------------------------------------------------
    |
    | Enjoy a life with multimail
    |
    */

    'use_default_mail_facade_in_tests' => true,

    'emails' => [
        'mail@no-reply' => [
            'pass' => env('MAILGUN_SECRET', ''),
            'username' => env('MAILGUN_USERNAME', ''),
            'from_name' => 'Maildoll',
        ],
    ],

    'provider' => [
        'default' => [
            'host' => 'smtp.mailgun.org',
            'port' => '587',
            'encryption' => 'tls',
        ],
    ],

];
