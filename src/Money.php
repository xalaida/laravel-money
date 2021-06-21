<?php

namespace Nevadskiy\Money;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Nevadskiy\Money\Casts\MoneyCast;
use Nevadskiy\Money\Converter\Converter;
use Nevadskiy\Money\Formatter\Formatter;
use Nevadskiy\Money\Models\Currency;

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
     * Convert the money to the string type.
     */
    public function __toString(): string
    {
        return $this->format();
    }

    /**
     * Create a new money instance from minor units.
     */
    public static function fromMajorUnits(float $amount, Currency $currency): self
    {
        return new static((int) ($amount * self::getMajorMultiplier($currency)), $currency);
    }

    /**
     * Create a new money instance from minor units.
     */
    public static function fromMinorUnits(int $amount, Currency $currency): self
    {
        return new static($amount, $currency);
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
        return $this->getMinorUnits() / self::getMajorMultiplier($this->currency);
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
     * @inheritDoc
     */
    public static function castUsing(array $arguments): MoneyCast
    {
        return app(MoneyCast::class, ['arguments' => $arguments]);
    }

    /**
     * Get the major units multiplier.
     */
    protected static function getMajorMultiplier(Currency $currency): int
    {
        return 10 ** $currency->precision;
    }

    /**
     * Get the money formatter.
     * TODO: probably extract it into static property (could be broken with Laravel Octane)
     */
    protected function getFormatter(): Formatter
    {
        return app(Formatter::class);
    }

    /**
     * Get the money converter.
     * TODO: probably extract it into static property (could be broken with Laravel Octane)
     */
    protected function getConverter(): Converter
    {
        return app(Converter::class);
    }
}
