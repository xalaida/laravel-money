<?php

namespace Nevadskiy\Money\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Nevadskiy\Money\Money;
use Nevadskiy\Money\ValueObjects\Rate;

class RateCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param Model $model
     * @param mixed $value
     */
    public function get($model, string $key, $value, array $attributes): Rate
    {
        return new Rate($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model $model
     * @param null|Money $value
     */
    public function set($model, string $key, $value, array $attributes): array
    {
        if (! $value instanceof Rate) {
            throw new InvalidArgumentException('The given value is not a Rate instance.');
        }

        return [
            $key => $value->getValue(),
        ];
    }
}
