<?php

namespace Nevadskiy\Money\Scalers;

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
        $scale = $this->getScale($currency);

        $majorUnits = round($amount / $this->getMajorMultiplier($currency), $scale, PHP_ROUND_HALF_DOWN);

        if ($scale === 0) {
            return (int) $majorUnits;
        }

        return $majorUnits;
    }

    /**
     * @inheritdoc
     */
    public function fromMajorUnits($amount, string $currency): int
    {
        return $amount * $this->getMajorMultiplier($currency);
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
