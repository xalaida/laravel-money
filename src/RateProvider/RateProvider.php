<?php

namespace Nevadskiy\Money\RateProvider;

use Nevadskiy\Money\Exceptions\SourceCurrencyRateMissingException;
use Nevadskiy\Money\Exceptions\TargetCurrencyRateMissingException;

interface RateProvider
{
    /**
     * Get exchange rate between the given currencies.
     *
     * @throws TargetCurrencyRateMissingException
     * @throws SourceCurrencyRateMissingException
     */
    public function getRate(string $sourceCurrency, string $targetCurrency): float;
}
