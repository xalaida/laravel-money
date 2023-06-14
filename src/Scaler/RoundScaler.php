<?php

namespace Nevadskiy\Money\Scaler;

use Nevadskiy\Money\Exceptions\CurrencyScaleMissingException;

class RoundScaler implements Scaler
{
    /**
     * The custom scales.
     *
     * @var array
     */
    protected $scales;

    /**
     * Make a new scaler instance.
     */
    public function __construct(array $scales = [])
    {
        $this->scales = $scales;
    }

    /**
     * @inheritdoc
     */
    public function toMajorUnits(int $amount, string $currency)
    {
        // @todo round to real scale.

        return $amount / $this->getMajorMultiplier($currency);
    }

    /**
     * @inheritdoc
     */
    public function fromMajorUnits($amount, string $currency): int
    {
        // @todo round to zero scale.

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
        if (! isset($this->scales[$currency])) {
            throw CurrencyScaleMissingException::for($currency);
        }

        return $this->scales[$currency];
    }
}
