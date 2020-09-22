<?php

declare(strict_types=1);

namespace Jeka\Money\Tests\Unit\Queries;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Jeka\Money\Database\Factories\CurrencyFactory;
use Jeka\Money\Models\Currency;
use Jeka\Money\Queries\CurrencyEloquentQueries;
use Jeka\Money\Tests\TestCase;

class CurrencyEloquentQueriesTest extends TestCase
{
    /** @test */
    public function it_can_get_currency_by_id(): void
    {
        $id = Currency::generateId();
        $currency = CurrencyFactory::new()->create(['id' => $id]);
        $anotherCurrency = CurrencyFactory::new()->create();

        $queries = new CurrencyEloquentQueries();

        self::assertTrue($queries->getById($id)->is($currency));
    }

    /** @test */
    public function it_throws_an_exception_if_currency_is_not_found_by_id(): void
    {
        $someCurrency = CurrencyFactory::new()->create();

        $queries = new CurrencyEloquentQueries();

        $this->expectException(ModelNotFoundException::class);

        self::assertTrue($queries->getById(Currency::generateId()));
    }

    /** @test */
    public function it_can_get_currency_by_code(): void
    {
        $currency = CurrencyFactory::new()->create(['code' => 'EUR']);
        $anotherCurrency = CurrencyFactory::new()->create();

        $queries = new CurrencyEloquentQueries();

        self::assertTrue($queries->getByCode('EUR')->is($currency));
    }

    /** @test */
    public function it_throws_an_exception_if_currency_is_not_found_by_key(): void
    {
        $someCurrency = CurrencyFactory::new()->create();

        $queries = new CurrencyEloquentQueries();

        $this->expectException(ModelNotFoundException::class);

        self::assertTrue($queries->getByCode('EUR'));
    }
}
