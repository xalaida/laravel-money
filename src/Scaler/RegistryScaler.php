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
    private $currencies;

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
    public function toMajorUnits(int $amount, string $currency = null)
    {
        return $amount / $this->getMajorMultiplier($currency);
    }

    /**
     * @inheritdoc
     */
    public function fromMajorUnits($amount, string $currency = null): int
    {
        return $amount * $this->getMajorMultiplier($currency);
    }

    /**
     * Get the major unit multiplier for the currency.
     */
    protected function getMajorMultiplier(string $currency): int
    {
        return 10 ** $this->currencies->get($currency)['scale'];
    }
}
