<?php

namespace Nevadskiy\Money\Converter;

use Nevadskiy\Money\Money;

interface Converter
{
    /**
     * Convert the money instance according to the given currency.
     */
    public function convert(Money $money, string $currency = null): Money;
}
