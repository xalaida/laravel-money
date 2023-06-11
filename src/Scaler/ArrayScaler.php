<?php

namespace Nevadskiy\Money\Scaler;

class ArrayScaler implements Scaler
{
    /**
     * The custom scales.
     *
     * @var array
     */
    protected $scales;

    /**
     * The default scale.
     *
     * @var int
     */
    protected $default;

    /**
     * Make a new scaler instance.
     */
    public function __construct(array $scales = [], int $default = 2)
    {
        $this->scales = $scales;
        $this->default = $default;
    }

    /**
     * @inheritdoc
     */
    public function toMajorUnits(int $amount, string $currency)
    {
        return $amount / $this->getMajorMultiplier($currency);
    }

    /**
     * @inheritdoc
     */
    public function fromMajorUnits($amount, string $currency): int
    {
        return round($amount * $this->getMajorMultiplier($currency), $this->getScale($currency), PHP_ROUND_HALF_DOWN);
    }

    /**
     * Get the major unit multiplier for the currency.
     */
    protected function getMajorMultiplier(string $currency): int
    {
        return 10 ** $this->getScale($currency);
    }

    /**
     * Get the scale for the currency.
     */
    protected function getScale(string $currency)
    {
        return $this->scales[$currency] ?? $this->default;
    }
}
