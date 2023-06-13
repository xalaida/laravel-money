<?php

namespace Nevadskiy\Money\Registry;

class IsoCurrencyRegistry
{
    public function all(): array
    {
        return [
            'USD' => [
                'scale' => 2,
            ],
            // ...
        ];
    }
}
