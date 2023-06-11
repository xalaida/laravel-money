<?php

namespace Nevadskiy\Money\RateProvider;

interface RateProvider
{
    /**
     * Get the collection of rates.
     *
     * @returns array<string, float>
     */
    public function getRates(): array;
}
