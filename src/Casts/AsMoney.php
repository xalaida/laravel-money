<?php

namespace Nevadskiy\Money\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Nevadskiy\Money\Money;

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
     * Indicates whether the currency should be converted before saving when does not match.
     *
     * @var bool
     */
    protected $convertBeforeSaving;

    /**
     * Make a new cast instance.
     */
    public function __construct(string $currency = null, bool $convertBeforeSaving = false)
    {
        if (Str::startsWith($currency, '[') && Str::endsWith($currency, ']')) {
            $this->currencyColumn = Str::between($currency, '[', ']');
        } else {
            $this->currency = $currency;
        }

        $this->convertBeforeSaving = $convertBeforeSaving;
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

        return new Money($value, $currency);
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

        // @todo handle currency mismatch.

        $columns = [
            $key => $value->getMinorUnits(),
        ];

        if ($this->currencyColumn) {
            return array_merge($columns, [
                $this->currencyColumn => $value->getCurrency(),
            ]);
        }

        return $columns;
    }
}
