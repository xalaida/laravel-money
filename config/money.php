<?php

use Nevadskiy\Money\Queries;

return [
    'currency' => 'USD',

    'scaler' => [
        'class' => Nevadskiy\Money\Scaler\RegistryScaler::class,
        'default' => 2,
        'scales' => [
            'BTC' => 8,
        ],
    ],

    'formatter' => [
        'class' => Nevadskiy\Money\Formatter\IntlFormatter::class,
        'locale' => null,
    ],

    'converter' => [
        'class' => Nevadskiy\Money\Converter\RegistryConverter::class,

        'converters' => [
            'rates' => [
                'USD' => 1.0,
                'EUR' => 0.9,
                'BTC' => 25000,
            ],
        ]
    ],

    // @todo "iso", "array", "database"
    'registry' => 'array',

    'registries' => [
        'array' => [
            'driver' => 'array',
            'currencies' => [
                'USD',
                'EUR',
                'UAH',
                'JPY',
            ],
        ],

        'database' => [
            'driver' => 'database',
            'model' => Nevadskiy\Money\Models\Currency::class,
        ]
    ],

    // <<< @todo

    'default_rate_provider' => 'open_exchange_rates',

    'rate_providers' => [
        'open_exchange_rates' => [
            'app_id' => env('OPEN_EXCHANGE_RATE_APP_ID', ''),
        ],
    ],

    'default_migrations' => true,

    // TODO: simplify that.
    'bindings' => [
        Queries\CurrencyQuery::class => [
            'implementation' => Queries\CurrencyEloquentQuery::class,

            'decorators' => [
                Queries\CurrencyCacheQuery::class,
            ],
        ],
    ],
];
