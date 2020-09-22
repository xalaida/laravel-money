<?php

declare(strict_types=1);

namespace Jeka\Money\Formatter;

use Jeka\Money\Money;
use NumberFormatter;

class IntlFormatter implements Formatter
{
    /**
     * The formatter locale.
     *
     * @var string
     */
    protected $locale;

    /**
     * IntlFormatter constructor.
     */
    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    /**
     * Set the formatter locale.
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * Format the given money according to the current locale.
     */
    public function format(Money $money): string
    {
        return $this->getNumberFormatter()
            ->formatCurrency($money->getMajorUnits(), $money->getCurrency()->code);
    }

    /**
     * Get the number formatter.
     */
    private function getNumberFormatter(): NumberFormatter
    {
        return new NumberFormatter($this->locale, NumberFormatter::CURRENCY);
    }
}
