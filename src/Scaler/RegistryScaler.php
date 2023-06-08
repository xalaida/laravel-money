<?php

namespace Nevadskiy\Money\Scaler;

class RegistryScaler implements Scaler
{
    /**
     * @inheritdoc
     */
    public function toMajorUnits(int $amount, string $currency = null): float
    {
        // @todo
    }

    /**
     * @inheritdoc
     */
    public function fromMajorUnits(float $amount, string $currency = null): int
    {
        // @todo
    }
}
