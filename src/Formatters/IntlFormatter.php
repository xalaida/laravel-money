<?php

namespace Nevadskiy\Money\Formatters;

use Nevadskiy\Money\Money;
use NumberFormatter;

class IntlFormatter implements Formatter
{
    /**
     * @inheritDoc
     */
    public function format(Money $money, string $locale): string
    {
        return $this->getNumberFormatter($locale)->formatCurrency($money->getMajorUnits(), $money->getCurrency());
    }

    /**
     * Get the number formatter.
     */
    protected function getNumberFormatter(string $locale): NumberFormatter
    {
        return new NumberFormatter($locale, NumberFormatter::CURRENCY);
    }
}
