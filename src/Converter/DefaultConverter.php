<?php

declare(strict_types=1);

namespace Nevadskiy\Money\Converter;

use Nevadskiy\Money\Exceptions\InvalidRateException;
use Nevadskiy\Money\Models\Currency;
use Nevadskiy\Money\Money;

class DefaultConverter implements Converter
{
    /**
     * @inheritDoc
     */
    public function setDefaultCurrency(Currency $currency): void
    {
        // TODO: Implement setDefaultCurrency() method.
    }

    /**
     * @inheritDoc
     */
    public function convert(Money $money, Currency $currency): Money
    {
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
        if (0 === $sourceCurrency->rate || 0 === $targetCurrency->rate) {
            throw new InvalidRateException();
        }
    }
}
