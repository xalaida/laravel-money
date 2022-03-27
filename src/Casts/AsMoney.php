<?php

namespace Nevadskiy\Money\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;
use Nevadskiy\Money\Queries\CurrencyQuery;
use Nevadskiy\Money\ValueObjects\Money;

/**
 * TODO: probably refactor using relation (requires one more model definition: relation to 'priceCurrency')
 * # Props:
 *  - cleaner cast class (can be used directly in the model $casts prop)
 *  - cleaner model class (clear price currency relation)
 *  - no extra dependencies in the money cast.
 *
 * # Cons:
 *  - extra definition for relation in model class
 *  - relation probably not useful in that case since it does not have logic (but probably can have it if user overrides it)
 */
class AsMoney implements CastsAttributes
{
    /**
     * The column name of the money amount.
     *
     * @var null|string
     */
    protected $amountColumnName;

    /**
     * The column name of the money currency.
     *
     * @var null|string
     */
    protected $currencyKeyColumnName;

    /**
     * Make a new cast instance.
     */
    public function __construct(array $arguments = [])
    {
        $this->amountColumnName = $arguments[0] ?? null;
        $this->currencyKeyColumnName = $arguments[1] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function get($model, string $key, $value, array $attributes): ?Money
    {
        $amountColumnName = $this->amountColumnName ?: $this->getAmountColumnName($key);
        $currencyKeyColumnName = $this->currencyKeyColumnName ?: $this->getCurrencyKeyColumnName($key);

        if ($this->isNullableAttributes($attributes, $amountColumnName, $currencyKeyColumnName)) {
            return null;
        }

        return new Money(
            $attributes[$amountColumnName],
            resolve(CurrencyQuery::class)->getById($attributes[$currencyKeyColumnName])
        );
    }

    /**
     * @inheritDoc
     */
    public function set($model, string $key, $value, array $attributes): array
    {
        if (null === $value) {
            return [];
        }

        $this->assertValueIsMoneyInstance($value);

        $amountColumnName = $this->amountColumnName ?: $this->getAmountColumnName($key);
        $currencyKeyColumnName = $this->currencyKeyColumnName ?: $this->getCurrencyKeyColumnName($key);

        return [
            $amountColumnName => $value->getMinorUnits(),
            $currencyKeyColumnName => $value->getCurrency()->getKey(),
        ];
    }

    /**
     * Assert that the given value is a money instance.
     *
     * @param mixed $value
     */
    protected function assertValueIsMoneyInstance($value): void
    {
        if (! $value instanceof Money) {
            throw new InvalidArgumentException('The given value is not a Money instance.');
        }
    }

    /**
     * Determine whether the money attributes is nullable.
     */
    protected function isNullableAttributes(array $attributes, string $amountColumnName, string $currencyKeyColumnName): bool
    {
        return null === $attributes[$amountColumnName]
            && null === $attributes[$currencyKeyColumnName];
    }

    /**
     * Get the amount column name.
     */
    protected function getAmountColumnName(string $key): string
    {
        return "{$key}_amount";
    }

    /**
     * Get the currency key column name.
     */
    protected function getCurrencyKeyColumnName(string $key): string
    {
        return "{$key}_currency_id";
    }
}
