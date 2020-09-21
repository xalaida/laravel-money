<?php

declare(strict_types=1);

namespace Jeka\Money;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Jeka\Money\Casts\MoneyCast;
use Jeka\Money\Formatter\Formatter;
use Jeka\Money\Models\Currency;

class Money implements Castable
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
     * TODO: rename into getAmount() to be consistent with database fields
     * TODO: keep getSubunits method as alias (for usability)
     * Get the money amount in subunits.
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
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * Returns money formatted according to the current locale.
     */
    public function format(): string
    {
        return $this->getFormatter()->format($this);
    }

    /**
     * Convert the money to the string type.
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

    /**
     * Get the name of the caster class to use when casting from / to this cast target.
     */
    public static function castUsing(array $arguments): MoneyCast
    {
        return app(MoneyCast::class);
    }
}
