<?php

return [
    'currency' => env('MONEY_CURRENCY', 'USD'),

    'fallback_currency' => env('MONEY_FALLBACK_CURRENCY', 'USD'),

    'currencies' => 'iso',

    'rate_provider' => env('MONEY_RATE_PROVIDER', 'array'),

    'rate_providers' => [
        'open_exchange_rates' => [
            'app_id' => env('OPEN_EXCHANGE_RATE_APP_ID'),
        ],
    ],
];
