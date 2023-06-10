<?php

namespace Nevadskiy\Money;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Nevadskiy\Money\Casts\AsMoney;
use Nevadskiy\Money\Converter\Converter;
use Nevadskiy\Money\Exceptions\CurrencyMismatchException;
use Nevadskiy\Money\Formatter\Formatter;
use Nevadskiy\Money\Scaler\Scaler;
use RuntimeException;
use JsonSerializable;

/**
 * @todo add aliases for subtract...
 * @todo add aliases for add...
 * @todo use the clone magic method...
 * @todo consider using just float instead of float|int if does not conflict with strict_types.
 */
class Money implements Castable, JsonSerializable
{
    /**
     * The default currency.
     *
     * @var string
     */
    protected static $defaultCurrency;

    /**
     * The amount of the money in minor units.
     *
     * @return int
     */
    protected $amount;

    /**
     * The currency of the money as 3-letter ISO 4217.
     *
     * @return string
     */
    protected $currency;

    /**
     * Make a new money instance.
     */
    public function __construct(int $amount, string $currency = null)
    {
        $this->amount = $amount;
        $this->currency = $currency ?: static::getDefaultCurrency();
    }

    /**
     * Create a new money instance from major units.
     */
    public static function fromMajorUnits(float $amount, string $currency = null): self
    {
        return new static(static::getScaler()->fromMajorUnits($amount, $currency), $currency);
    }

    /**
     * Create a new money instance from minor units.
     */
    public static function fromMinorUnits(int $amount, string $currency = null): self
    {
        return new static($amount, $currency);
    }

    /**
     * Get the amount of the money.
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Get the currency of the money.
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Get the amount of the money in minor units.
     */
    public function getMinorUnits(): int
    {
        return $this->getAmount();
    }

    /**
     * Get the amount of the money in major units.
     */
    public function getMajorUnits(): float
    {
        return static::getScaler()->toMajorUnits($this->getMinorUnits(), $this->getCurrency());
    }

    /**
     * Add the given money to the money instance.
     */
    public function plus(Money $money, bool $convert = false): self
    {
        if (! $convert) {
            $this->ensureCurrencyMatches($money);
        }

        return $this->clone($this->getAmount() + $money->convert($this->getCurrency())->getAmount());
    }

    /**
     * Subtract the given money from the money instance.
     */
    public function minus(Money $money, bool $convert = false): self
    {
        if (! $convert) {
            $this->ensureCurrencyMatches($money);
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
    public function clone(int $amount = null, string $currency = null): self
    {
        return new Money($amount ?: $this->getAmount(), $currency ?: $this->getCurrency());
    }

    /**
     * Format the money instance.
     */
    public function format(string $locale = null): string
    {
        return $this->formatUsing(static::getFormatter(), $locale);
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
    public function convert(string $currency = null): self
    {
        return $this->convertUsing(static::getConverter(), $currency);
    }

    /**
     * Convert the money instance using the given converter.
     */
    public function convertUsing(Converter $converter, string $currency = null): self
    {
        return $converter->convert($this, $currency);
    }

    /**
     * Get the string representation of the money instance.
     */
    public function __toString(): string
    {
        return $this->format();
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        return [
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
        ];
    }

    /**
     * Get the money scaler instance.
     */
    protected static function getScaler(): Scaler
    {
        return resolve(Scaler::class);
    }

    /**
     * Get the money formatter instance.
     */
    protected static function getFormatter(): Formatter
    {
        return resolve(Formatter::class);
    }

    /**
     * Get the money converter instance.
     */
    protected static function getConverter(): Converter
    {
        return resolve(Converter::class);
    }

    /**
     * Set the default currency of the money.
     */
    public static function setDefaultCurrency(string $currency): void
    {
        static::$defaultCurrency = $currency;
    }

    /**
     * Get the default currency of the money.
     */
    public static function getDefaultCurrency(): string
    {
        // @todo return config('money.currency');

        if (! isset(static::$defaultCurrency)) {
            throw new RuntimeException('The default currency is not set.');
        }

        return static::$defaultCurrency;
    }

    /**
     * Ensure the currency of the given money matches the currency of the current money.
     */
    protected function ensureCurrencyMatches(Money $that): void
    {
        if ($this->getCurrency() !== $that->getCurrency()) {
            throw new CurrencyMismatchException();
        }
    }

    /**
     * @inheritdoc
     */
    public static function castUsing(array $arguments): string
    {
        return AsMoney::class;
    }
}
