<?php

namespace Nevadskiy\Money\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Nevadskiy\Money\ValueObjects\Money;

class DefaultMoneyCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param Model $model
     * @param mixed $value
     */
    public function get($model, string $key, $value, array $attributes): ?Money
    {
        if ($value === null) {
            return null;
        }

        return new Money($value);
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

        return [$key => $value->getAmount()];
    }

    /**
     * Assert that the given value is a money instance.
     *
     * @param $value
     */
    protected function assertValueIsMoneyInstance($value): void
    {
        if (! $value instanceof Money) {
            throw new InvalidArgumentException('The given value is not a Money instance.');
        }
    }
}
