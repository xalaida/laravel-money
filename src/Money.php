<?php

namespace Nevadskiy\Money;

use Nevadskiy\Money\Converter\Converter;
use Nevadskiy\Money\Exceptions\CurrencyMismatchException;
use Nevadskiy\Money\Formatter\Formatter;
use Nevadskiy\Money\Models\Currency;
use RuntimeException;

/**
 * @todo add aliases for subtract
 * @todo add aliases for add
 */
class Money
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
        $this->currency = $currency ?: static::resolveDefaultCurrency();
    }

    /**
     * Create a new money instance from major units.
     */
    public static function fromMajorUnits(float $amount, Currency $currency = null): self
    {
        $currency = $currency ?: static::resolveDefaultCurrency();

        return new static((int) ($amount * $currency->getMajorMultiplier()), $currency);
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
        return $this->getMinorUnits() / $this->currency->getMajorMultiplier();
    }

    /**
     * Get the money currency.
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * Add the given money to the money instance.
     */
    public function plus(Money $money, bool $convert = false): self
    {
        if (! $convert) {
            $this->assertMoneyCurrencyMatches($money);
        }

        return $this->clone($this->getAmount() + $money->convert($this->getCurrency())->getAmount());
    }

    /**
     * Subtract the given money from the money instance.
     */
    public function minus(Money $money, bool $convert = false): self
    {
        if (! $convert) {
            $this->assertMoneyCurrencyMatches($money);
        }

        return $this->clone($this->getAmount() - $money->convert($this->getCurrency())->getAmount());
    }

    public function plusPercentage(float $percentage): self
    {
        // TODO: complete the method.
    }

    public function minusPercentage(float $percentage): self
    {
        // TODO: complete the method.
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
     *
     * @todo feature clone magic method.
     */
    public function clone(int $amount = null, Currency $currency = null): self
    {
        return new Money($amount ?: $this->getAmount(), $currency ?: $this->getCurrency());
    }

    /**
     * Returns formatted money according to the locale.
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
     * Returns converted money according to the given currency.
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
     * Set the resolver function for the default currency.
     *
     * @todo use different registry drivers: array, database, json, remote http, etc.
     */
    public static function resolveDefaultCurrencyUsing(callable $resolver): void
    {
        static::$defaultCurrencyResolver = $resolver;
    }

    /**
     * Resolve the default currency.
     */
    public static function resolveDefaultCurrency(): Currency
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
        return resolve(Formatter::class);
    }

    /**
     * Get the money converter.
     */
    protected function getConverter(): Converter
    {
        return resolve(Converter::class);
    }

    /**
     * Get the string representation of the money instance.
     */
    public function __toString(): string
    {
        return $this->format();
    }

    /**
     * Assert that the given currency matches the current currency.
     */
    private function assertMoneyCurrencyMatches(Money $money): void
    {
        if (! $this->getCurrency()->is($money->getCurrency())) {
            throw new CurrencyMismatchException();
        }
    }
}
