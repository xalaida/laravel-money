<?php

declare(strict_types=1);

namespace Jeka\Money\Tests\Unit\Listeners;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Testing\Concerns\InteractsWithContainer;
use Jeka\Money\Database\Factories\CurrencyFactory;
use Jeka\Money\Events\CurrencyCreated;
use Jeka\Money\Events\CurrencyDeleted;
use Jeka\Money\Events\CurrencyUpdated;
use Jeka\Money\Queries\CurrencyCacheQueries;
use Jeka\Money\Tests\TestCase;

class InvalidateCurrencyCacheTest extends TestCase
{
    use InteractsWithContainer;

    /** @test */
    public function it_invalidates_currency_cache_on_currency_created_event(): void
    {
        $currency = CurrencyFactory::USD();

        $queries = $this->spy(CurrencyCacheQueries::class);

        $this->app[Dispatcher::class]->dispatch(new CurrencyCreated($currency));

        $queries->shouldHaveReceived('invalidate')->once();
    }

    /** @test */
    public function it_invalidates_currency_cache_on_currency_updated_event(): void
    {
        $currency = CurrencyFactory::USD();

        $queries = $this->spy(CurrencyCacheQueries::class);

        $this->app[Dispatcher::class]->dispatch(new CurrencyUpdated($currency));

        $queries->shouldHaveReceived('invalidate')->once();
    }

    /** @test */
    public function it_invalidates_currency_cache_on_currency_deleted_event(): void
    {
        $currency = CurrencyFactory::USD();

        $queries = $this->spy(CurrencyCacheQueries::class);

        $this->app[Dispatcher::class]->dispatch(new CurrencyDeleted($currency));

        $queries->shouldHaveReceived('invalidate')->once();
    }
}