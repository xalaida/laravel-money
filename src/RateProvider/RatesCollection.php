<?php

declare(strict_types=1);

namespace Nevadskiy\Money\RateProvider;

class RatesCollection
{
    /**
     * @var Rate[]
     */
    private $rates;

    /**
     * RatesCollection constructor.
     *
     * @param Rate ...$rates
     */
    public function __construct(Rate ...$rates)
    {
        $this->rates = $rates;
    }

    /**
     * Map rates by their codes.
     */
    public function mapByCodes(): array
    {
        $rates = [];

        foreach ($this->rates as $rate) {
            $rates[$rate->getCode()] = $rate;
        }

        return $rates;
    }
}
