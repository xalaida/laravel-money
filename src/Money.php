<?php

namespace Nevadskiy\Money;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Nevadskiy\Money\Casts\AsMoney;
use Nevadskiy\Money\Converter\Converter;
use Nevadskiy\Money\Exceptions\CurrencyMismatchException;
use Nevadskiy\Money\Formatter\Formatter;
use Nevadskiy\Money\Scaler\Scaler;
use JsonSerializable;

class Money implements Castable, JsonSerializable
{
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
        $this->currency = $currency ?? static::getDefaultCurrency();
    }

    /**
     * Create a new money instance from major units.
     *
     * @param float|int $amount
     */
    public static function fromMajorUnits($amount, string $currency = null): self
    {
        $currency = $currency ?? static::getDefaultCurrency();

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
     * Create a new zero money instance.
     */
    public static function zero(string $currency = null): self
    {
        return new static(0, $currency);
    }

    /**
     * Get the amount of the money.
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Set a new amount of the money.
     */
    public function setAmount(int $amount = null): self
    {
        $clone = clone $this;
        $clone->amount = $amount ?? $this->getAmount();

        return $clone;
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
     *
     * @return float|int
     */
    public function getMajorUnits()
    {
        return static::getScaler()->toMajorUnits($this->getMinorUnits(), $this->getCurrency());
    }

    /**
     * Add the given money to the current money.
     */
    public function plus(Money $money, bool $convert = false): self
    {
        if (! $convert) {
            $this->ensureCurrencyMatches($money);
        }

        // @todo do not convert when same currency.
        return $this->setAmount($this->getAmount() + $money->convert($this->getCurrency())->getAmount());
    }

    /**
     * Alias to add the given money to the current money.
     */
    public function add(Money $money, bool $convert = false): self
    {
        return $this->plus($money, $convert);
    }

    /**
     * Subtract the given money from the current money.
     */
    public function minus(Money $money, bool $convert = false): self
    {
        if (! $convert) {
            $this->ensureCurrencyMatches($money);
        }

        // @todo do not convert when same currency.
        return $this->setAmount($this->getAmount() - $money->convert($this->getCurrency())->getAmount());
    }

    /**
     * Alias to subtract the given money from the current money.
     */
    public function subtract(Money $money, bool $convert = false): self
    {
        return $this->minus($money, $convert);
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
        return $this->setAmount($this->getAmount() * $multiplier);
    }

    /**
     * Divide the money instance.
     *
     * @param float|int $divisor
     */
    public function divide($divisor): self
    {
        return $this->setAmount($this->getAmount() / $divisor);
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
        return $formatter->format($this, $locale ?? static::getDefaultLocale());
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
        $currency = $currency ?? static::getDefaultCurrency();

        if ($currency === $this->getCurrency()) {
            return clone $this;
        }

        return $converter->convert($this, $currency);
    }

    /**
     * Set the default currency of the money.
     */
    public static function setDefaultCurrency(string $currency): void
    {
        static::getConfig()->set('money.currency', $currency);
    }

    /**
     * Get the default currency of the money.
     */
    public static function getDefaultCurrency(): string
    {
        return static::getConfig()->get('money.currency');
    }

    /**
     * Get the default locale of the application.
     */
    public static function getDefaultLocale(): string
    {
        return static::getConfig()->get('app.locale');
    }

    /**
     * Get the config instance.
     */
    protected static function getConfig(): Config
    {
        return resolve('config');
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
     * @inheritdoc
     */
    public static function castUsing(array $arguments): string
    {
        return AsMoney::class;
    }

    /**
     * @inheritdoc
     *
     * @todo use custom serializer service.
     */
    public function jsonSerialize(): array
    {
        return [
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
        ];
    }

    /**
     * Get the string representation of the money instance.
     */
    public function __toString(): string
    {
        return $this->format();
    }
}
