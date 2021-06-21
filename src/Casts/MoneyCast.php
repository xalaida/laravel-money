<?php

declare(strict_types=1);

namespace Nevadskiy\Money\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Nevadskiy\Money\Money;
use Nevadskiy\Money\Queries\CurrencyQueries;

class MoneyCast implements CastsAttributes
{
    /**
     * @var CurrencyQueries
     */
    private $queries;

    /**
     * MoneyCast constructor.
     */
    public function __construct(CurrencyQueries $queries)
    {
        $this->queries = $queries;
    }

    /**
     * Cast the given value.
     *
     * @param Model $model
     * @param mixed $value
     */
    public function get($model, string $key, $value, array $attributes): ?Money
    {
        $amountKeyName = $this->getAmountKeyName($key);
        $currencyIdKeyName = $this->getCurrencyIdKeyName($key);

        if ($this->isNullableAttributes($attributes, $amountKeyName, $currencyIdKeyName)) {
            return null;
        }

        return new Money($attributes[$amountKeyName], $this->queries->getById($attributes[$currencyIdKeyName]));
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model $model
     * @param Money|null $value
     */
    public function set($model, string $key, $value, array $attributes): array
    {
        if (null === $value) {
            return [];
        }

        $this->assertValueIsMoneyInstance($value);

        return [
            $this->getAmountKeyName($key) => $value->getAmount(),
            $this->getCurrencyIdKeyName($key) => $value->getCurrency()->id,
        ];
    }

    /**
     * Assert that the given value is a money instance.
     *
     * @param $value
     */
    private function assertValueIsMoneyInstance($value): void
    {
        if (! $value instanceof Money) {
            throw new InvalidArgumentException('The given value is not a Money instance.');
        }
    }

    /**
     * Get the money amount key name.
     */
    private function getAmountKeyName(string $key): string
    {
        return "{$key}_amount";
    }

    /**
     * Get the money currency ID key name.
     */
    private function getCurrencyIdKeyName(string $key): string
    {
        return "{$key}_currency_id";
    }

    /**
     * Determine whether the money attributes is nullable.
     */
    private function isNullableAttributes(array $attributes, string $amountKeyName, string $currencyIdKeyName): bool
    {
        return null === $attributes[$amountKeyName]
            && null === $attributes[$currencyIdKeyName];
    }
}
