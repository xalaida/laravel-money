<?php

namespace Nevadskiy\Money\Converter;

use Nevadskiy\Money\Exceptions\InvalidRateException;
use Nevadskiy\Money\Money;
use Nevadskiy\Money\Registry\CurrencyRegistry;

class RegistryConverter implements Converter
{
    /**
     * The currency registry instance.
     *
     * @var CurrencyRegistry
     */
    private $registry;

    /**
     * Make a new converter instance.
     */
    public function __construct(CurrencyRegistry $registry)
    {
        $this->registry = $registry;
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

        return new Money($this->getConvertedAmount($money, $currency), $currency);
    }

    /**
     * Get the converted amount of the money.
     */
    protected function getConvertedAmount(Money $money, string $currency): int
    {
        if ($money->getCurrency() === $currency) {
            return $money->getAmount();
        }

        return (int) (($money->getAmount() * $this->getRate($currency)) / $this->getRate($money->getCurrency()));
    }

    /**
     * Get rate of the currency.
     */
    protected function getRate(string $currency)
    {
        $rate = $this->registry->get($currency)['rate'];

        $this->ensureRateIsValid($rate);

        return $rate;
    }

    /**
     * Ensure that the given rate is valid.
     */
    protected function ensureRateIsValid(float $rate): void
    {
        if ($rate <= 0) {
            throw new InvalidRateException('The rate of the currency cannot be negative or zero.');
        }
    }
}
