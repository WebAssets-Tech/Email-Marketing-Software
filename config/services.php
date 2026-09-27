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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'paytm-wallet' => [
        'env' => env('PAYTM_ENVIRONMENT'), // values : (local | production)
        'merchant_id' => env('PAYTM_MERCHANT_ID'),
        'merchant_key' => env('PAYTM_MERCHANT_KEY'),
        'merchant_website' => env('PAYTM_WEBSITE'),
        'channel' => env('PAYTM_CHANNEL'),
        'industry_type' => env('PAYTM_INDUSTRY_TYPE'),
    ],
    'payment_gateways' => [
        'instamojo' => [
            'type' => 'app',
            'client_id' => env('IM_API_KEY'),
            'client_secret' => env('IM_AUTH_TOKEN'),
            'username' => 'FOO',
            /** In case of user based authentication**/
            'password' => 'XXXXXXXX',
            /** In case of user based authentication**/
            'is_sandbox' => env('IM_SANDBOX', true),
        ],
        'mollie' => [
            'api_key' => env('MOLLIE_KEY', 'test')
        ],
        'khalti' => [
            'secret' => env('KHALTI_SECRET'),
            'is_sandbox' => env('KHALTI_SANDBOX', false)
        ],
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

];
