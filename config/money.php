<?php

return [
    // TODO: make it optional.
    'default_currency_code' => 'USD',

    'default_rate_provider' => 'open_exchange_rates',

    'rate_providers' => [
        'open_exchange_rates' => [
            'app_id' => env('OPEN_EXCHANGE_RATE_APP_ID', ''),
        ],
    ],

    'default_migrations' => true,
];
