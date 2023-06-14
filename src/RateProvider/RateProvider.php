<?php

namespace Nevadskiy\Money\RateProvider;

use Nevadskiy\Money\Exceptions\CurrencyRateMissingException;

interface RateProvider
{
    /**
     * Get exchange rate between the given currencies.
     *
     * @throws CurrencyRateMissingException
     */
    public function getRate(string $sourceCurrency, string $targetCurrency): float;
}
