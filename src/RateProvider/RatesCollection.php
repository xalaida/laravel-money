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
}
