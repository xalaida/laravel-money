<?php

namespace Nevadskiy\Money\Converter;

use Nevadskiy\Money\Money;
use Nevadskiy\Money\RateProvider\RateProvider;

class NewBaseCurrencyConverter implements Converter
{
    /**
     * The rate provider instance.
     *
     * @var RateProvider
     */
    protected $rateProvider;

    /**
     * Make a new converter instance.
     */
    public function __construct(RateProvider $rateProvider)
    {
        $this->rateProvider = $rateProvider;
    }

    /**
     * @inheritDoc
     */
    public function convert(Money $money, string $currency): Money
    {
        return Money::fromMajorUnits(
           $money->getMajorUnits() * $this->getRateBetween($money->getCurrency(), $currency), $currency
        );
    }

    /**
     * Get a rate between the given currencies.
     *
     * @todo use base currency & do not call rates when it is base.
     *
     * @return float|int
     */
    protected function getRateBetween(string $sourceCurrency, string $targetCurrency)
    {
        if ($sourceCurrency === $targetCurrency) {
            return 1;
        }

        return $this->getRateToBase($targetCurrency) / $this->getRateToBase($sourceCurrency);
    }

    /**
     * Get rate to the base currency.
     *
     * @return float|int
     */
    protected function getRateToBase(string $currency)
    {
        return $this->rateProvider->getRates()[$currency];
    }
}
