<?php

namespace Nevadskiy\Money\Exceptions;

use RuntimeException;

class CurrencyRateMissingException extends RuntimeException
{
    /**
     * Make a new exception instance for the given currency.
     */
    public static function for(string $currency): self
    {
        return new static(sprintf("Rate is missing for currency [%s].", $currency));
    }
}
