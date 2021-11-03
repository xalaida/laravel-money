<?php

namespace Nevadskiy\Money\Listeners;

use Nevadskiy\Money\Queries\CurrencyCacheQuery;

class InvalidateCurrencyCache
{
    /**
     * The currency query instance.
     *
     * @var CurrencyCacheQuery
     */
    private $currencies;

    /**
     * Create the event listener.
     */
    public function __construct(CurrencyCacheQuery $currencies)
    {
        $this->currencies = $currencies;
    }

    /**
     * Handle the event.
     */
    public function handle(): void
    {
        $this->currencies->invalidate();
    }
}
