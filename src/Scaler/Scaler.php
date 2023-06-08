<?php

namespace Nevadskiy\Money\Scaler;

interface Scaler
{
    /**
     * Scale to given amount of the money in minor units to an amount in major units.
     */
    public function toMajorUnits(int $amount, string $currency = null): float;

    /**
     * Scale to given amount of the money in major units to an amount in minor units.
     */
    public function fromMajorUnits(float $amount, string $currency = null): int;
}
