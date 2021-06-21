<?php

declare(strict_types=1);

namespace Nevadskiy\Money\Tests\Unit\Queries;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Nevadskiy\Money\Database\Factories\CurrencyFactory;
use Nevadskiy\Money\Models\Currency;
use Nevadskiy\Money\Queries\CurrencyEloquentQueries;
use Nevadskiy\Money\Tests\TestCase;

class CurrencyEloquentQueriesTest extends TestCase
{
    public function test_it_can_get_currency_by_id(): void
    {
        $id = Currency::generateId();
        $currency = CurrencyFactory::new()->create(['id' => $id]);
        $anotherCurrency = CurrencyFactory::new()->create();

        $queries = new CurrencyEloquentQueries();

        static::assertTrue($queries->getById($id)->is($currency));
    }

    public function test_it_throws_an_exception_if_currency_is_not_found_by_id(): void
    {
        $someCurrency = CurrencyFactory::new()->create();

        $queries = new CurrencyEloquentQueries();

        $this->expectException(ModelNotFoundException::class);

        static::assertTrue($queries->getById(Currency::generateId()));
    }

    public function test_it_can_get_currency_by_code(): void
    {
        $currency = CurrencyFactory::new()->create(['code' => 'EUR']);
        $anotherCurrency = CurrencyFactory::new()->create();

        $queries = new CurrencyEloquentQueries();

        static::assertTrue($queries->getByCode('EUR')->is($currency));
    }

    public function test_it_throws_an_exception_if_currency_is_not_found_by_key(): void
    {
        $someCurrency = CurrencyFactory::new()->create();

        $queries = new CurrencyEloquentQueries();

        $this->expectException(ModelNotFoundException::class);

        static::assertTrue($queries->getByCode('EUR'));
    }
}
