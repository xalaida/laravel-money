<?php

namespace Nevadskiy\Money\Scaler;

interface Scaler
{
    /**
     * Scale to given amount of the money in minor units to an amount in major units.
     *
     * @return float|int
     */
    public function toMajorUnits(int $amount, string $currency = null);

    /**
     * Scale to given amount of the money in major units to an amount in minor units.
     *
     * @param float|int $amount
     */
    public function fromMajorUnits($amount, string $currency = null): int;
}
