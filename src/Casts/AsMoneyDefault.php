<?php

namespace Nevadskiy\Money\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;
use Nevadskiy\Money\Money;

class AsMoneyDefault implements CastsAttributes
{
    /**
     * @inheritDoc
     */
    public function get($model, string $key, $value, array $attributes): ?Money
    {
        if (null === $value) {
            return null;
        }

        return new Money($value);
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

        return [
            $key => $value->getMinorUnits(),
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
}
