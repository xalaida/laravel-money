<?php

return [
    'currency' => 'USD',

    'rate_provider' => env('MONEY_RATE_PROVIDER', 'array'),

    // @todo >>>

    'fallback_currency' => 'USD', // Currency to fallback when impossible to convert...

    'rate_providers' => [
        'cache' => [
            'driver' => 'cache',
            'provider' => 'open_exchange_rates',
            'ttl' => 60 * 24
        ],

        'array' => [
            'driver' => 'array',
            'rates' => [
                'USD' => 1.0,
                'EUR' => 0.92515185,
            ],
        ],

        'open_exchange_rates' => [
            'app_id' => env('OPEN_EXCHANGE_RATE_APP_ID'),
            'driver' => 'open_exchange_rates',
            'cache' => true,
            'ttl' => 60 * 24,
        ],
    ],
];
