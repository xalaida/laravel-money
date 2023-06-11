<?php

namespace Nevadskiy\Money\Scaler;

use Nevadskiy\Money\Registry\CurrencyRegistry;

class RegistryScaler implements Scaler
{
    /**
     * The currency registry instance.
     *
     * @var CurrencyRegistry
     */
    protected $currencies;

    /**
     * Make a new scaler instance.
     */
    public function __construct(CurrencyRegistry $currencies)
    {
        $this->currencies = $currencies;
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
        return $this->currencies->get($currency)['scale'];
    }
}
