<?php

namespace Nevadskiy\Money\RateProvider;

use Nevadskiy\Money\Exceptions\SourceCurrencyRateMissingException;
use Nevadskiy\Money\Exceptions\TargetCurrencyRateMissingException;

abstract class BaseCurrencyRateProvider implements RateProvider
{
    /**
     * Get all rates of the provider.
     */
    abstract protected function getRates(): array;

    /**
     * Get the base currency of the provider.
     */
    abstract protected function getBaseCurrency(): string;

    /**
     * @inheritdoc
     */
    public function getRate(string $sourceCurrency, string $targetCurrency): float
    {
        // @todo cover this with test.
        if ($sourceCurrency === $targetCurrency) {
            return 1;
        }

        // @todo cover this with test.
        if ($sourceCurrency === $this->getBaseCurrency()) {
            return $this->getTargetToBaseRate($targetCurrency);
        }

        // @todo cover this with test.
        if ($targetCurrency === $this->getBaseCurrency()) {
            return 1 / $this->getSourceToBaseRate($sourceCurrency);
        }

        // @todo cover this with test.
        return $this->getTargetToBaseRate($targetCurrency) / $this->getSourceToBaseRate($sourceCurrency);
    }

    /**
     * Get rate to the base currency.
     */
    protected function getTargetToBaseRate(string $currency): float
    {
        $rates = $this->getRates();

        if (! isset($rates[$currency])) {
            throw TargetCurrencyRateMissingException::for($currency);
        }

        return $rates[$currency];
    }

    /**
     * Get rate to the base currency.
     */
    protected function getSourceToBaseRate(string $currency): float
    {
        $rates = $this->getRates();

        if (! isset($rates[$currency])) {
            throw SourceCurrencyRateMissingException::for($currency);
        }

        return $rates[$currency];
    }
}
