<?php

namespace Nevadskiy\Money\Registries;

class CurrencyRegistry
{
    /**
     * The registered currency list.
     */
    protected $currencies = [];

    /**
     * Make a new registry instance.
     */
    public function __construct(array $currencies)
    {
        $this->currencies = $currencies;
    }

    /**
     * Register the given currency with options.
     */
    public function set(string $currency, array $options = []): void
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

    /**
     * Determine whether the given currency exists in the registry.
     */
    public function has(string $currency): bool
    {
        return isset($this->currencies[$currency]);
    }

    /**
     * Get the currency list.
     */
    public function all(): array
    {
        return $this->currencies;
    }

    /**
     * Pluck an array of values with the given option for each currency.
     */
    public function pluck(string $option): array
    {
        return array_map(static function (array $options) use ($option) {
            return $options[$option];
        }, $this->all());
    }
}
