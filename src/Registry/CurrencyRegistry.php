<?php

namespace Nevadskiy\Money\Registry;

interface CurrencyRegistry
{
    /**
     * Register the given currency with options.
     */
    public function set(string $currency, array $options = []): void;

    /**
     * Get options of the currency.
     */
    public function get(string $currency): array;

    /**
     * Determine whether the given currency has been registered.
     */
    public function has(string $currency): bool;

    /**
     * Get the currency list.
     */
    public function all(): array;

    /**
     * Pluck an array of values with the given option for each currency.
     */
    public function pluck(string $option): array;
}
