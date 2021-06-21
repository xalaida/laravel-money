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
        $amountColumnName = $this->getAmountColumnName($key);
        $currencyKeyColumnName = $this->getCurrencyKeyColumnName($key);

        if ($this->isNullableAttributes($attributes, $amountColumnName, $currencyKeyColumnName)) {
            return null;
        }

        // TODO: refactor with currency relation.
        return new Money($attributes[$amountColumnName], $this->queries->getById($attributes[$currencyKeyColumnName]));
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model      $model
     * @param null|Money $value
     */
    public function set($model, string $key, $value, array $attributes): array
    {
        if (null === $value) {
            return [];
        }

        $this->assertValueIsMoneyInstance($value);

        return [
            $this->getAmountColumnName($key) => $value->getAmount(),
            $this->getCurrencyKeyColumnName($key) => $value->getCurrency()->getKey(),
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
     * Get the amount column name.
     */
    private function getAmountColumnName(string $key): string
    {
        return "{$key}_amount";
    }

    /**
     * Get the currency key column name.
     */
    private function getCurrencyKeyColumnName(string $key): string
    {
        return "{$key}_currency_id";
    }

    /**
     * Determine whether the money attributes is nullable.
     */
    private function isNullableAttributes(array $attributes, string $amountColumnName, string $currencyKeyColumnName): bool
    {
        return null === $attributes[$amountColumnName]
            && null === $attributes[$currencyKeyColumnName];
    }
}
