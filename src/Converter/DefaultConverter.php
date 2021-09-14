<?php

namespace Nevadskiy\Money\Converter;

use Nevadskiy\Money\Exceptions\DefaultCurrencyMissingException;
use Nevadskiy\Money\Models\Currency;
use Nevadskiy\Money\ValueObjects\Money;

class DefaultConverter implements Converter
{
    /**
     * The default converter currency.
     *
     * @var Currency|null
     */
    protected $defaultCurrency;

    /**
     * Make a new converter instance.
     */
    public function __construct(Currency $defaultCurrency = null)
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
        if (is_null($this->defaultCurrency)) {
            throw new DefaultCurrencyMissingException();
        }

        return $this->defaultCurrency;
    }

    /**
     * @inheritDoc
     */
    public function convert(Money $money, Currency $currency = null): Money
    {
        $currency = $currency ?: $this->getDefaultCurrency();

        return new Money($this->getConvertedAmount($money, $currency), $currency);
    }

    /**
     * Get the converted money amount.
     *
     * @return float|int
     */
    protected function getConvertedAmount(Money $money, Currency $currency)
    {
        return ($money->getAmount() * $currency->rate->getValue()) / $money->getCurrency()->rate->getValue();
    }
}
