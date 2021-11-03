<?php

namespace Nevadskiy\Money\ValueObjects;

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
     * Returns money formatted according to the locale.
     */
    public function format(string $locale = null): string
    {
        return $this->formatUsing($this->getFormatter(), $locale);
    }

    /**
     * Format the money instance using the given formatter.
     */
    public function formatUsing(Formatter $formatter, string $locale = null): string
    {
        return $formatter->format($this, $locale);
    }

    /**
     * Returns money converted according to the given currency.
     */
    public function convert(Currency $currency = null): self
    {
        return $this->convertUsing($this->getConverter(), $currency);
    }

    /**
     * Convert the money instance using the given converter.
     */
    public function convertUsing(Converter $converter, Currency $currency = null): self
    {
        return $converter->convert($this, $currency);
    }

    /**
     * Get the major unit multiplier.
     */
    protected static function getMajorMultiplier(Currency $currency): int
    {
        return 10 ** $currency->precision;
    }

    /**
     * Get the money formatter.
     *
     * @todo: refactor to resolve from the static prop.
     */
    protected function getFormatter(): Formatter
    {
        return app(Formatter::class);
    }

    /**
     * Get the money converter.
     *
     * @todo: refactor to resolve from the static prop.
     */
    protected function getConverter(): Converter
    {
        return app(Converter::class);
    }

    /**
     * @inheritDoc
     */
    public static function castUsing(array $arguments): MoneyCast
    {
        return app(MoneyCast::class, ['arguments' => $arguments]);
    }
}
