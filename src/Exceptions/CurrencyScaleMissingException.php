<?php

namespace Nevadskiy\Money\Exceptions;

use RuntimeException;

class CurrencyScaleMissingException extends RuntimeException
{
    /**
     * The currency of the exception.
     *
     * @var string
     */
    protected $currency;

    /**
     * Make a new exception instance for the given currency.
     */
    public static function for(string $currency): self
    {
        $e = new static(sprintf("Scale is missing for currency [%s].", $currency));
        $e->currency = $currency;

        return $e;
    }

    /**
     * Get the currency of the exception.
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
}
