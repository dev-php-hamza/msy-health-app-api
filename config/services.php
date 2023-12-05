<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, SparkPost and others. This file provides a sane default
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
        'token' => env('POSTMARK_APP_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],
    
    'google' => [
        'client_id' => env('GOOGLE_ID'),
        'client_secret' => env('GOOGLE_SECRET'),
        'redirect' => env('APP_URL').'/login/google/callback',
        'autocomplete_client_id' => env('PLACES_ID'), // this is for google maps also
    ],

    'oneSignal' => [
        'app_key' => env('ONE_SIGNAL_APP_KEY'),
    ],

        /*
    |--------------------------------------------------------------------------
    | twilio Key for sending sms
    |--------------------------------------------------------------------------
    |
    | These keys are used to send sms. sms for account verification
    |
    */

    'twilio' => [
        'TWILIO_AUTH_TOKEN'  => env('TWILIO_AUTH_TOKEN'),
        'TWILIO_ACCOUNT_SID' => env('TWILIO_ACCOUNT_SID'),
        'TWILIO_NUMBER'      => env('TWILIO_NUMBER')
    ],
];
