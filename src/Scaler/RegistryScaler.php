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
        return $amount * $this->getMajorMultiplier($currency);
    }

    /**
     * Get the major unit multiplier for the currency.
     */
    protected function getMajorMultiplier(string $currency): int
    {
        return 2; // @todo use registry currency.
    }
}
