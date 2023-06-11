<?php

namespace Nevadskiy\Money\Converter;

use Nevadskiy\Money\Money;
use Nevadskiy\Money\Registry\CurrencyRegistry;

class RegistryConverter implements Converter
{
    /**
     * The currency registry instance.
     *
     * @var CurrencyRegistry
     */
    private $currencies;

    /**
     * Make a new converter instance.
     */
    public function __construct(CurrencyRegistry $currencies)
    {
        $this->currencies = $currencies;
    }

//    /**
//     * @inheritDoc
//     */
//    public static function useDefaultCurrency(string $currency): void
//    {
//        $this->defaultCurrency = $currency;
//    }

//    /**
//     * Get the default currency instance.
//     */
//    public function getDefaultCurrency(): string
//    {
//        if (! $this->defaultCurrency) {
//            throw new RuntimeException("Default currency is not set.");
//        }
//
//        return $this->defaultCurrency;
//    }

    /**
     * @inheritDoc
     */
    public function convert(Money $money, string $currency): Money
    {
        if ($money->getCurrency() === $currency) {
            return clone $money;
        }

        return Money::fromMajorUnits(
           $money->getMajorUnits() * $this->getRateBetween($money->getCurrency(), $currency), $currency
        );
    }

    /**
     * Get a rate between the given currencies.
     *
     * @return float|int
     */
    protected function getRateBetween(string $sourceCurrency, string $targetCurrency)
    {
        return $this->getRateToBase($targetCurrency) / $this->getRateToBase($sourceCurrency);
    }

    /**
     * Get rate of the currency.
     *
     * @return float|int
     */
    protected function getRateToBase(string $currency)
    {
        return $this->currencies->get($currency)['rate'];
    }
}
