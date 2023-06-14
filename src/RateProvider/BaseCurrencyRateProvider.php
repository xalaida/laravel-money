<?php

namespace Nevadskiy\Money\RateProvider;

use Nevadskiy\Money\Exceptions\CurrencyRateMissingException;

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
            return $this->getRateToBase($targetCurrency);
        }

        // @todo cover this with test.
        if ($targetCurrency === $this->getBaseCurrency()) {
            return 1 / $this->getRateToBase($sourceCurrency);
        }

        // @todo cover this with test.
        return $this->getRateToBase($targetCurrency) / $this->getRateToBase($sourceCurrency);
    }

    /**
     * Get rate to the base currency.
     */
    protected function getRateToBase(string $currency): float
    {
        $rates = $this->getRates();

        if (! isset($rates[$currency])) {
            throw CurrencyRateMissingException::for($currency);
        }

        return $rates[$currency];
    }
}
