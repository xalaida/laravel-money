<?php

namespace Nevadskiy\Money\Registry;

use Illuminate\Support\Manager;

class CurrencyRegistryManager extends Manager
{
    /**
     * @inheritdoc
     */
    public function getDefaultDriver(): string
    {
        return $this->config['money']['currencies'] ?? 'iso';
    }

    /**
     * Make the "ISO 4217" currency registry.
     */
    protected function createIsoDriver(): CurrencyRegistry
    {
        return IsoCurrencies::make();
    }

    /**
     * Make the "Open Exchange Rates" currency registry.
     */
    protected function createOpenExchangeRatesDriver(): CurrencyRegistry
    {
        return OpenExchangeRatesCurrencies::make();
    }

    /**
     * Make the "Stripe" currency registry.
     */
    protected function createStripeDriver(): CurrencyRegistry
    {
        return StripeCurrencies::make();
    }
}
