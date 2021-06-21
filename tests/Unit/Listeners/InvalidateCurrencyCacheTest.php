<?php

namespace Nevadskiy\Money\Tests\Unit\Listeners;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Testing\Concerns\InteractsWithContainer;
use Nevadskiy\Money\Database\Factories\CurrencyFactory;
use Nevadskiy\Money\Events\CurrencyCreated;
use Nevadskiy\Money\Events\CurrencyDeleted;
use Nevadskiy\Money\Events\CurrencyUpdated;
use Nevadskiy\Money\Queries\CurrencyCacheQueries;
use Nevadskiy\Money\Tests\TestCase;

class InvalidateCurrencyCacheTest extends TestCase
{
    use InteractsWithContainer;

    public function test_it_invalidates_currency_cache_on_currency_created_event(): void
    {
        $currency = CurrencyFactory::new()->create();

        $queries = $this->spy(CurrencyCacheQueries::class);

        $this->app[Dispatcher::class]->dispatch(new CurrencyCreated($currency));

        $queries->shouldHaveReceived('invalidate')->once();
    }

    public function test_it_invalidates_currency_cache_on_currency_updated_event(): void
    {
        $currency = CurrencyFactory::new()->create();

        $queries = $this->spy(CurrencyCacheQueries::class);

        $this->app[Dispatcher::class]->dispatch(new CurrencyUpdated($currency));

        $queries->shouldHaveReceived('invalidate')->once();
    }

    public function test_it_invalidates_currency_cache_on_currency_deleted_event(): void
    {
        $currency = CurrencyFactory::new()->create();

        $queries = $this->spy(CurrencyCacheQueries::class);

        $this->app[Dispatcher::class]->dispatch(new CurrencyDeleted($currency));

        $queries->shouldHaveReceived('invalidate')->once();
    }
}
