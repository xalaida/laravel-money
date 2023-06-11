<?php

namespace Nevadskiy\Money\Scaler;

class ArrayScaler implements Scaler
{
    /**
     * The default scale.
     *
     * @var int
     */
    private $default;

    /**
     * The custom scales.
     *
     * @var array
     */
    private $scales;

    /**
     * Make a new scaler instance.
     */
    public function __construct(int $default = 2, array $scales = [])
    {
        $this->default = $default;
        $this->scales = $scales;
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
