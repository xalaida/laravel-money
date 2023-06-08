<?php

namespace Nevadskiy\Money\Formatter;

use Nevadskiy\Money\Money;
use NumberFormatter;

/**
 * @todo add RegistryFormatter with options & symbols & custom formats.
 */
class IntlFormatter implements Formatter
{
    /**
     * The default formatter locale.
     *
     * @var string
     */
    protected $defaultLocale;

    /**
     * Make a new formatted instance.
     */
    public function __construct(string $locale)
    {
        $this->defaultLocale = $locale;
    }

    /**
     * @inheritDoc
     */
    public function setDefaultLocale(string $locale): void
    {
        $this->defaultLocale = $locale;
    }

    /**
     * @inheritDoc
     */
    public function format(Money $money, string $locale = null): string
    {
        $locale = $locale ?: $this->defaultLocale;

        // @todo get currency list.
        return $this->getNumberFormatter($locale)->formatCurrency($money->getMajorUnits(), $money->getCurrency());
    }

    /**
     * Get the number formatter.
     */
    protected function getNumberFormatter(string $locale): NumberFormatter
    {
        return new NumberFormatter($locale, NumberFormatter::CURRENCY);
    }
}
