<?php

namespace Nevadskiy\Money\Registry;

class CurrencyRegistry
{
    protected $currencies = [];

    public function set(string $currency, array $options = [])
    {
        $this->currencies[$currency] = $options;
    }

    public function get(string $currency): array
    {
        return $this->currencies[$currency];
    }

    public function has(string $currency): bool
    {
        return isset($this->currencies[$currency]);
    }

    public function all(): array
    {
        return $this->currencies;
    }
}
