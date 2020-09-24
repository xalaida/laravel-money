<?php

declare(strict_types=1);

namespace Jeka\Money\Formatter;

use Jeka\Money\Money;

interface Formatter
{
    /**
     * TODO: think about handling this as separate LocaleFormatter
     * Set the formatter locale.
     */
    public function setLocale(string $locale): void;

    /**
     * Format the given money according to the current locale.
     */
    public function format(Money $money): string;
}
