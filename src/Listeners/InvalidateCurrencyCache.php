<?php

declare(strict_types=1);

namespace Jeka\Money\Listeners;

use Jeka\Money\Events\CurrencyEvent;
use Jeka\Money\Queries\CurrencyCacheQueries;

class InvalidateCurrencyCache
{
    /**
     * @var CurrencyCacheQueries
     */
    private $queries;

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
