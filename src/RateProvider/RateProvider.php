<?php

declare(strict_types=1);

namespace Jeka\Money\RateProvider;

interface RateProvider
{
    /**
     * Get the rates collection of the provider.
     *
     * @return RatesCollection
     */
    public function getRates(): RatesCollection;
}
