<?php

namespace Nevadskiy\Money\ValueObjects;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Nevadskiy\Money\Casts\MoneyCast;
use Nevadskiy\Money\Converter\Converter;
use Nevadskiy\Money\Formatter\Formatter;
use Nevadskiy\Money\Models\Currency;
use RuntimeException;

class Money implements Castable
{
    /**
     * The default currency resolver function.
     *
     * @var callable
     */
    protected static $defaultCurrencyResolver;

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
     * Make a new money instance.
     */
    public function __construct(int $amount, Currency $currency = null)
    {
        $this->amount = $amount;
        $this->currency = $currency ?: static::getDefaultCurrency();
    }

    /**
     * Create a new money instance from minor units.
     */
    public static function fromMajorUnits(float $amount, Currency $currency = null): self
    {
        $currency = $currency ?: static::getDefaultCurrency();

        return new static((int) ($amount * static::getMajorMultiplier($currency)), $currency);
    }

    /**
     * Create a new money instance from minor units.
     */
    public static function fromMinorUnits(int $amount, Currency $currency = null): self
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
        return $this->getMinorUnits() / static::getMajorMultiplier($this->currency);
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
     * Multiply the money instance.
     *
     * @param float|int $multiplier
     */
    public function multiply($multiplier): self
    {
        return $this->clone($this->getAmount() * $multiplier);
    }

    /**
     * Divide the money instance.
     *
     * @param float|int $divisor
     */
    public function divide($divisor): self
    {
        return $this->clone($this->getAmount() / $divisor);
    }

    /**
     * Get a clone of the money instance.
     */
    public function clone(int $amount = null, Currency $currency = null): self
    {
        return new Money($amount ?: $this->getAmount(), $currency ?: $this->getCurrency());
    }

    /**
     * Get the default currency.
     */
    public static function getDefaultCurrency(): Currency
    {
        return static::resolveDefaultCurrency();
    }

    /**
     * Set the default currency resolver function.
     */
    public static function resolveDefaultCurrencyUsing(callable $resolver): void
    {
        static::$defaultCurrencyResolver = $resolver;
    }

    /**
     * Resolve the default currency.
     */
    protected static function resolveDefaultCurrency(): Currency
    {
        if (! isset(static::$defaultCurrencyResolver)) {
            throw new RuntimeException("Cannot resolve the default currency.");
        }

        return call_user_func(static::$defaultCurrencyResolver);
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
     * @inheritDoc
     */
    public static function castUsing(array $arguments): MoneyCast
    {
        return app(MoneyCast::class, ['arguments' => $arguments]);
    }

    /**
     * Convert the money to the string type.
     */
    public function __toString(): string
    {
        return $this->format();
    }
}
