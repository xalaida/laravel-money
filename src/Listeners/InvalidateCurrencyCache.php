<?php

namespace Nevadskiy\Money\Listeners;

use Nevadskiy\Money\Events\CurrencyEvent;
use Nevadskiy\Money\Queries\CurrencyCacheQueries;

class InvalidateCurrencyCache
{
    /**
     * @var CurrencyCacheQueries
     */
    protected $queries;

    /**
     * Create the event listener.
     */
    public function __construct(CurrencyCacheQueries $queries)
    {
        $this->queries = $queries;
    }

    /**
     * Handle the event.
     */
    public function handle(CurrencyEvent $event): void
    {
        $this->queries->invalidate();
    }
}
