<?php

declare(strict_types=1);

namespace Jeka\Money;

use Jeka\Money\Formatter\Formatter;
use Jeka\Money\Models\Currency;

class Money
{
    /**
     * The money amount in subunits.
     *
     * @return int
     */
    private $subunits;

    /**
     * The money currency.
     *
     * @return Currency
     */
    private $currency;

    /**
     * Money constructor.
     */
    public function __construct(int $subunits, Currency $currency)
    {
        $this->subunits = $subunits;
        $this->currency = $currency;
    }

    /**
     * Get the money amount in subunits.
     *
     * @return int
     */
    public function getSubunits(): int
    {
        return $this->subunits;
    }

    /**
     * Get the money amount in super units.
     *
     * @return float|int
     */
    public function getSuperUnits()
    {
        return $this->getSubunits() / (10 ** $this->currency->precision);
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

    /**
     * Returns money formatted according to the current locale.
     *
     * @return string
     */
    public function format(): string
    {
        return $this->getFormatter()->format($this);
    }

    /**
     * Convert the money to the string type.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->format();
    }

    /**
     * Get the money formatter.
     */
    protected function getFormatter(): Formatter
    {
        return app(Formatter::class);
    }
}
