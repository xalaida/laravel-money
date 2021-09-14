<?php

namespace Nevadskiy\Money\RateProvider;

use Nevadskiy\Money\ValueObjects\Rate;

interface RateProvider
{
    /**
     * Get the collection of rates.
     *
     * @returns array<string,Rate>
     */
    public function getRates(): array;
}
