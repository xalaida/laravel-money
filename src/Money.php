<?php

declare(strict_types=1);

namespace Jeka\Money;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Jeka\Money\Casts\MoneyCast;
use Jeka\Money\Converter\Converter;
use Jeka\Money\Formatter\Formatter;
use Jeka\Money\Models\Currency;

class Money implements Castable
{
    /**
     * The money amount in minor units.
     *
     * @return int
     */
    protected $amount;

    /**
     * The money currency.
     *
     * @return Currency
     */
    protected $currency;

    /**
     * Money constructor.
     */
    public function __construct(int $amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * Get the money amount in minor units.
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Alias for getter of the money amount in minor units.
     */
    public function getMinorUnits(): int
    {
        return $this->getAmount();
    }

    /**
     * Get the money amount in major units.
     *
     * @return float|int
     */
    public function getMajorUnits()
    {
        return $this->getMinorUnits() / (10 ** $this->currency->precision);
    }

    /**
     * Get the money currency.
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * Returns money formatted according to the current locale.
     */
    public function format(): string
    {
        return $this->getFormatter()->format($this);
    }

    /**
     * Returns money converted according to the given currency.
     */
    public function convert(Currency $currency): self
    {
        return $this->getConverter()->convert($this, $currency);
    }

    /**
     * Convert the money to the string type.
     */
    public function __toString(): string
    {
        return $this->format();
    }

    /**
     * Get the money formatter.
     */
    protected function getFormatter(): Formatter
    {
        return app(Formatter::class);
    }

    /**
     * Get the money converter.
     */
    protected function getConverter(): Converter
    {
        return app(Converter::class);
    }

    /**
     * {@inheritDoc}
     */
    public static function castUsing(array $arguments): MoneyCast
    {
        return app(MoneyCast::class);
    }
}
