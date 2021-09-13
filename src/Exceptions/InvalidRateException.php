<?php

namespace Nevadskiy\Money\Exceptions;

use InvalidArgumentException;

class InvalidRateException extends InvalidArgumentException
{
    /**
     * Make a negative invalid rate exception instance.
     */
    public static function negative(): self
    {
        return new static('Currency rate cannot be negative or zero.');
    }
}
