<?php

namespace Nevadskiy\Money\Converter;

use Nevadskiy\Money\Exceptions\InvalidRateException;
use Nevadskiy\Money\Models\Currency;
use Nevadskiy\Money\Money;

class DefaultConverter implements Converter
{
    /**
     * The default currency instance.
     *
     * @var Currency
     */
    protected $defaultCurrency;

    /**
     * Make a new converter instance.
     */
    public function __construct(Currency $defaultCurrency)
    {
        $this->defaultCurrency = $defaultCurrency;
    }

    /**
     * @inheritDoc
     */
    public function setDefaultCurrency(Currency $currency): void
    {
        $this->defaultCurrency = $currency;
    }

    /**
     * Get the default currency instance.
     */
    public function getDefaultCurrency(): Currency
    {
        return $this->defaultCurrency;
    }

    /**
     * @inheritDoc
     */
    public function convert(Money $money, Currency $currency = null): Money
    {
        $currency = $currency ?: $this->getDefaultCurrency();

        $this->assertNoZeroRates($money->getCurrency(), $currency);

        return new Money($this->getConvertedAmount($money, $currency), $currency);
    }

    /**
     * Get the converted money amount.
     *
     * @return float|int
     */
    protected function getConvertedAmount(Money $money, Currency $currency)
    {
        return ($money->getAmount() * $currency->rate) / $money->getCurrency()->rate;
    }

    /**
     * Assert that currency rates don't equal to zero.
     */
    protected function assertNoZeroRates(Currency $sourceCurrency, Currency $targetCurrency): void
    {
        if ((float) 0 === (float) $sourceCurrency->rate || (float) 0 === (float) $targetCurrency->rate) {
            throw new InvalidRateException();
        }
    }
}
