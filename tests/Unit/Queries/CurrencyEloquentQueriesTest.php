<?php

namespace Nevadskiy\Money\Tests\Unit\Queries;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Nevadskiy\Money\Database\Factories\CurrencyFactory;
use Nevadskiy\Money\Queries\CurrencyEloquentQuery;
use Nevadskiy\Money\Tests\TestCase;

class CurrencyEloquentQueriesTest extends TestCase
{
    public function test_it_can_get_currency_by_id(): void
    {
        [$currency, $anotherCurrency] = CurrencyFactory::new()
            ->count(2)
            ->create();

        $queries = resolve(CurrencyEloquentQuery::class);

        static::assertTrue($queries->getById($currency->getKey())->is($currency));
    }

    public function test_it_throws_an_exception_if_currency_is_not_found_by_id(): void
    {
        CurrencyFactory::new()->create();

        $queries = resolve(CurrencyEloquentQuery::class);

        $this->expectException(ModelNotFoundException::class);

        static::assertTrue($queries->getById(999));
    }

    public function test_it_can_get_currency_by_code(): void
    {
        $currency = CurrencyFactory::new()->create(['code' => 'EUR']);
        $anotherCurrency = CurrencyFactory::new()->create();

        $queries = resolve(CurrencyEloquentQuery::class);

        static::assertTrue($queries->getByCode('EUR')->is($currency));
    }

    public function test_it_throws_an_exception_if_currency_is_not_found_by_code(): void
    {
        CurrencyFactory::new()->create();

        $queries = resolve(CurrencyEloquentQuery::class);

        $this->expectException(ModelNotFoundException::class);

        static::assertTrue($queries->getByCode('EUR'));
    }
}
