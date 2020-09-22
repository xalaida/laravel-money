<?php

declare(strict_types=1);

namespace Jeka\Money\Tests\Unit;

use Jeka\Money\Database\Factories\CurrencyFactory;
use Jeka\Money\Money;
use Jeka\Money\Tests\TestCase;

class MoneyTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated_with_amount_in_minor_units_and_currency(): void
    {
        $currency = CurrencyFactory::new()->create();
        $money = new Money(100, $currency);

        self::assertEquals(100, $money->getAmount());
        self::assertTrue($money->getCurrency()->is($currency));
    }

    /** @test */
    public function it_can_be_formatted_to_string_according_to_current_locale(): void
    {
        $currency = CurrencyFactory::new()->create(['code' => 'USD']);
        $money = new Money(100, $currency);

        self::assertEquals('$1.00', $money->format());
    }

    /** @test */
    public function it_can_be_formatted_to_string_according_to_new_locale(): void
    {
        $currency = CurrencyFactory::new()->create(['code' => 'USD']);
        $money = new Money(100, $currency);

        $this->app->setLocale('ru');

        self::assertEquals('1,00 $', $money->format());
    }

    /** @test */
    public function it_can_determine_major_units_amount(): void
    {
        $currency = CurrencyFactory::new()->create(['code' => 'USD', 'precision' => 3]);
        $money = new Money(3000, $currency);

        self::assertEquals(3, $money->getMajorUnits());
    }

    /** @test */
    public function it_calculates_major_units_correctly_if_precision_is_zero(): void
    {
        $currency = CurrencyFactory::new()->create(['code' => 'MRU', 'precision' => 0]);
        $money = new Money(3500, $currency);

        self::assertEquals(3500, $money->getMajorUnits());
    }
}
