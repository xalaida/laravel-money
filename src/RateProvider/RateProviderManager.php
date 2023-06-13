<?php

namespace Nevadskiy\Money\RateProvider;

use Illuminate\Support\Manager;

class RateProviderManager extends Manager implements RateProvider
{
    /**
     * @inheritdoc
     */
    public function getRates(): array
    {
        return $this->driver()->getRates();
    }

    /**
     * @inheritdoc
     */
    public function getDefaultDriver(): string
    {
        return $this->config['money']['rate_provider'];
    }

    /**
     * Create an instance of the open currency exchange rate provider.
     */
    protected function createArrayDriver(): RateProvider
    {
        return $this->container->get(ArrayRateProvider::class);
    }

    /**
     * Create an instance of the open currency exchange rate provider.
     */
    protected function createOpenExchangeRatesDriver(): RateProvider
    {
        return $this->container->get(OpenExchangeRateProvider::class);
    }
}
