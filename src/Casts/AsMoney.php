<?php

namespace Nevadskiy\Money\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Nevadskiy\Money\Exceptions\CurrencyMismatchException;
use Nevadskiy\Money\Money;

/**
 * @example AsMoney::class.':[currency],U,0'
 */
class AsMoney implements CastsAttributes
{
    /**
     * The currency of the money.
     *
     * @var string|null
     */
    protected $currency;

    /**
     * Indicates whether the currency should be taken from the column.
     *
     * @var string|null
     */
    protected $currencyColumn;

    /**
     * Indicates if it should use major units for storing money.
     *
     * @var bool
     */
    protected $asMajorUnits = true;

    /**
     * The default amount when prop is null.
     *
     * @todo use this.
     */
    protected $default;

    /**
     * Make a new cast instance.
     */
    public function __construct(string $currency = null, string $units = 'u', int $default = null)
    {
        if (Str::startsWith($currency, '[') && Str::endsWith($currency, ']')) {
            $this->currencyColumn = Str::between($currency, '[', ']');
        } else {
            $this->currency = $currency;
        }

        $this->asMajorUnits = $units === 'U';
        $this->default = $default;
    }

    /**
     * @inheritDoc
     */
    public function get($model, string $key, $value, array $attributes): ?Money
    {
        if (is_null($value)) {
            return null;
        }

        $currency = $this->currencyColumn
            ? $attributes[$this->currencyColumn]
            : $this->currency;

        if ($this->asMajorUnits) {
            return $this->newFromMajorUnits($value, $currency);
        }

        return $this->newFromMinorUnits($value, $currency);
    }

    /**
     * @inheritDoc
     */
    public function set($model, string $key, $value, array $attributes): ?array
    {
        if (null === $value) {
            return null;
        }

        if (! $value instanceof Money) {
            throw new InvalidArgumentException('The given value is not a Money instance.');
        }

        $columns = [
            $key => $this->asMajorUnits
                ? $value->getMajorUnits()
                : $value->getAmount(),
        ];

        if ($this->currencyColumn) {
            return array_merge($columns, [
                $this->currencyColumn => $value->getCurrency(),
            ]);
        }

        if ($this->getCurrency() !== $value->getCurrency()) {
            throw new CurrencyMismatchException();
        }

        return $columns;
    }

    /**
     * Get the currency of the cast.
     */
    protected function getCurrency(): string
    {
        return $this->currency ?? Money::getDefaultCurrency();
    }

    /**
     * Make a new money instance from the minor units.
     */
    protected function newFromMinorUnits(int $amount, string $currency = null): Money
    {
        return new Money($amount, $currency);
    }

    /**
     * Make a new money instance from the major units.
     *
     * @param int|float $amount
     */
    protected function newFromMajorUnits($amount, string $currency = null): Money
    {
        return Money::fromMajorUnits($amount, $currency);
    }
}
