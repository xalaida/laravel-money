<?php

namespace Nevadskiy\Money\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;
use Nevadskiy\Money\ValueObjects\Rate;

class AsRate implements CastsAttributes
{
    /**
     * @inheritDoc
     */
    public function get($model, string $key, $value, array $attributes): Rate
    {
        return new Rate($value);
    }

    /**
     * @inheritDoc
     */
    public function set($model, string $key, $value, array $attributes): array
    {
        $this->assertValueIsRateInstance($value);

        return [
            $key => $value->getValue(),
        ];
    }

    /**
     * Assert that the given value is a rate instance.
     *
     * @param mixed $value
     */
    private function assertValueIsRateInstance($value): void
    {
        if (! $value instanceof Rate) {
            throw new InvalidArgumentException('The given value is not a Rate instance.');
        }
    }
}
