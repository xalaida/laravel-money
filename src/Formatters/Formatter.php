<?php

namespace Nevadskiy\Money\Formatters;

use Nevadskiy\Money\Money;

interface Formatter
{
    /**
     * Format the given money instance according to the locale.
     */
    public function format(Money $money, string $locale): string;
}
