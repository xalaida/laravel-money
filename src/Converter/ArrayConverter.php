<?php

namespace Nevadskiy\Money\Converter;

use Nevadskiy\Money\Money;

class ArrayConverter implements Converter
{
    /**
     * The currency exchange rates.
     *
     * @var array
     */
    protected $rates;

    /**
     * Make a new converter instance.
     */
    public function __construct(array $rates)
    {
        $this->rates = $rates;
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
     * @return float|int
     * @todo use base currency.
     */
    protected function getRateBetween(string $sourceCurrency, string $targetCurrency)
    {
        if ($sourceCurrency === $targetCurrency) {
            return 1;
        }

        return $this->getRateToBase($targetCurrency) / $this->getRateToBase($sourceCurrency);
    }

    /**
     * Get rate of the currency.
     *
     * @return float|int
     */
    protected function getRateToBase(string $currency)
    {
        return $this->rates[$currency];
    }
}
