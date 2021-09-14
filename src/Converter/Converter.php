<?php

namespace Nevadskiy\Money\Converter;

use Nevadskiy\Money\Models\Currency;
use Nevadskiy\Money\ValueObjects\Money;

interface Converter
{
    /**
     * Set the default converter currency.
     */
    public function setDefaultCurrency(Currency $currency): void;

    /**
     * Convert the given money instance according to the currency.
     */
    public function convert(Money $money, Currency $currency = null): Money;
}
