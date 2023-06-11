<?php

namespace Nevadskiy\Money\Formatter;

use Nevadskiy\Money\Money;

interface Formatter
{
    /**
     * Format the given money instance according to the locale.
     */
    public function format(Money $money, string $locale): string;
}
