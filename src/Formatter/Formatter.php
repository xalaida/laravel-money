<?php

namespace Nevadskiy\Money\Formatter;

use Nevadskiy\Money\Money;

interface Formatter
{
    /**
     * Set the default formatter locale.
     */
    public function setDefaultLocale(string $locale): void;

    /**
     * Format the given money instance according to the locale.
     */
    public function format(Money $money, string $locale = null): string;
}
