<?php

namespace Nevadskiy\Money\RateProvider;

use Illuminate\Support\Manager;

class RateProviderManager extends Manager implements RateProvider
{
    /**
     * @inheritdoc
     */
    public function getRate(string $sourceCurrency, string $targetCurrency): float
    {
        return $this->driver()->getRate($sourceCurrency, $targetCurrency);
    }

    /**
     * @inheritdoc
     */
    public function getDefaultDriver(): string
    {
        return $this->config['money']['rate_provider'];
    }

    /**
     * Make the "array" rate provider.
     */
    protected function createArrayDriver(): RateProvider
    {
        return $this->container->get(ArrayRateProvider::class);
    }

    /**
     * Make the "Open Exchange Rates" rate provider.
     */
    protected function createOpenExchangeRatesDriver(): RateProvider
    {
        return $this->container->get(OpenExchangeRateProvider::class);
    }
}
