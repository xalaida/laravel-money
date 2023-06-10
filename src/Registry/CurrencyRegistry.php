<?php

namespace Nevadskiy\Money\Registry;

class CurrencyRegistry
{
    /**
     * The currency list.
     *
     * @var array
     */
    protected $currencies = [];

    /**
     * Register the given currency.
     */
    public function set(string $currency, array $options): void
    {
        $this->currencies[$currency] = $options;
    }

    /**
     * Get options of the currency.
     */
    public function get(string $currency): array
    {
        return $this->currencies[$currency];
    }
}
