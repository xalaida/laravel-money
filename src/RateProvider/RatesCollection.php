<?php

declare(strict_types=1);

namespace Jeka\Money\RateProvider;

use ArrayIterator;
use IteratorAggregate;

class RatesCollection implements IteratorAggregate
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
     * @inheritDoc
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->rates);
    }

    /**
     * Map rates by their codes.
     *
     * @return array
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
