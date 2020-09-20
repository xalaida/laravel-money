<?php

namespace Jeka\Money\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Jeka\Money\Money;
use Jeka\Money\Queries\CurrencyQueries;

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
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return Money|null
     */
    public function get($model, string $key, $value, array $attributes): ?Money
    {
        if (is_null($attributes["{$key}_amount"]) && is_null($attributes["{$key}_currency_id"])) {
            return null;
        }

        return new Money(
            $attributes["{$key}_amount"],
            $this->queries->getById($attributes["{$key}_currency_id"])
        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  Model  $model
     * @param  string  $key
     * @param Money|null $value
     * @param  array  $attributes
     * @return array
     */
    public function set($model, string $key, $value, array $attributes): array
    {
        if (is_null($value)) {
            return [];
        }

        $this->assertValueIsMoneyInstance($value);

        return [
            "{$key}_amount" => $value->getSubunits(),
            "{$key}_currency_id" => $value->getCurrency()->id,
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
}
