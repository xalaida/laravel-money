<?php

return [
    'currency' => 'USD',

    'rate_provider' => env('MONEY_RATE_PROVIDER', 'array'),

    'rate_providers' => [
        'open_exchange_rates' => [
            'app_id' => env('OPEN_EXCHANGE_RATE_APP_ID'),
        ],
    ],
];
