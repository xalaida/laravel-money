<?php

declare(strict_types=1);

namespace Jeka\Money;

use Jeka\Money\Models\Currency;

class Money
{
    /**
     * The money amount in subunits.
     *
     * @return int
     */
    private $amount;

    /**
     * The money currency.
     *
     * @return Currency
     */
    private $currency;

    /**
     * Money constructor.
     */
    public function __construct(int $amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * Get the money amount in subunits.
     *
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Get the money currency.
     *
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}
