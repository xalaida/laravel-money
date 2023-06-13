<?php

namespace Nevadskiy\Money\RateProvider;

class ArrayRateProvider implements RateProvider
{
    /**
     * The currency exchange rate list.
     *
     * @var array
     */
    private $rates;

    /**
     * Make a new provider instance.
     */
    public function __construct(array $rates)
    {
        $this->rates = $rates;
    }

    /**
     * @inheritDoc
     */
    public function getRates(): array
    {
        return $this->rates;
    }
}
