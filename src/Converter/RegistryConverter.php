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
    public function convert(Money $money, string $currency = null): Money
    {
        // @todo possibility to specify custom default...
        $currency = $currency ?: Money::getDefaultCurrency();

        if ($money->getCurrency() === $currency) {
            return clone $money;
        }

        return Money::fromMajorUnits(
            $money->getMajorUnits() * $this->getRate($money->getCurrency(), $currency), $currency
        );
    }

    /**
     * Get a rate between the given currencies.
     */
    protected function getRate(string $sourceCurrency, string $targetCurrency): float
    {
        return $this->getCurrencyRate($targetCurrency) / $this->getCurrencyRate($sourceCurrency);
    }

    /**
     * Get rate of the currency.
     */
    protected function getCurrencyRate(string $currency)
    {
        return $this->currencies->get($currency)['rate'];
    }
}
