<?php

namespace Nevadskiy\Money\RateProviders;

use Nevadskiy\Money\Money;

class ArrayRateProvider extends BaseCurrencyRateProvider
{
    /**
     * The currency exchange rates.
     *
     * @var array
     */
    protected $rates;

    /**
     * The base currency.
     *
     * @var string
     */
    protected $baseCurrency;

    /**
     * Make a new provider instance.
     */
    public function __construct(array $rates = [], string $baseCurrency = null)
    {
        $this->rates = $rates;
        $this->baseCurrency = $baseCurrency ?? Money::getDefaultCurrency();
    }

    /**
     * @inheritDoc
     */
    protected function getRates(): array
    {
        return $this->rates;
    }

    /**
     * @inheritDoc
     */
    protected function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }
}
