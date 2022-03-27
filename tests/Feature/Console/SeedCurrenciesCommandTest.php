<?php

namespace Nevadskiy\Money\Tests\Feature\Console;

use Nevadskiy\Money\Database\Factories\CurrencyFactory;
use Nevadskiy\Money\Models\Currency;
use Nevadskiy\Money\Tests\TestCase;

class SeedCurrenciesCommandTest extends TestCase
{
    /** @test */
    public function it_seeds_currencies(): void
    {
        $this->assertDatabaseCount(Currency::class, 0);

        $this->artisan('currencies:seed');

        $this->assertDatabaseCount(Currency::class, count(require __DIR__.'/../../../resources/currencies.php'));
    }

    /** @test */
    public function it_seeds_only_specified_currencies(): void
    {
        $this->artisan('currencies:seed', [
            'currencies' => ['USD', 'EUR']
        ]);

        $this->assertDatabaseCount(Currency::class, 2);
    }

    /** @test */
    public function it_truncates_currencies_before_seeding(): void
    {
        CurrencyFactory::new()->default()->create();

        $this->assertDatabaseCount(Currency::class, 1);

        $this->artisan('currencies:seed', [
            'currencies' => ['USD', 'EUR'],
            '--truncate' => true
        ]);

        $this->assertDatabaseCount(Currency::class, 2);
    }
}
