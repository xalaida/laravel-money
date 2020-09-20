<?php

declare(strict_types=1);

namespace Jeka\Money\Formatter;

use Jeka\Money\Money;

interface Formatter
{
    /**
     * Set the formatter locale.
     */
    public function setLocale(string $locale): void;

    /**
     * Format the given money according to the current locale.
     */
    public function format(Money $money): string;
}
