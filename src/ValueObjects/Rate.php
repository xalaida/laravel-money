<?php

namespace Nevadskiy\Money\ValueObjects;

use Nevadskiy\Money\Exceptions\InvalidRateException;

class Rate
{
    /**
     * The rate value.
     *
     * @var float
     */
    private $value;

    /**
     * Make a new rate instance.
     */
    public function __construct(float $value)
    {
        $this->assertValueIsValid($value);
        $this->value = $value;
    }

    /**
     * Get the rate value.
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * Assert that the given value is valid.
     */
    private function assertValueIsValid(float $value): void
    {
        if ($value <= 0) {
            throw new InvalidRateException('Currency rate cannot be negative or zero.');
        }
    }
}
