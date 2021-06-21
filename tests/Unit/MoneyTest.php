<?php

declare(strict_types=1);

namespace Nevadskiy\Money\Tests\Unit;

use Nevadskiy\Money\Database\Factories\CurrencyFactory;
use Nevadskiy\Money\Money;
use Nevadskiy\Money\Tests\TestCase;

class MoneyTest extends TestCase
{
    public function test_it_can_be_instantiated_with_amount_in_minor_units_and_currency(): void
    {
        $currency = CurrencyFactory::new()->create();
        $money = new Money(100, $currency);

        static::assertSame(100, $money->getAmount());
        static::assertTrue($money->getCurrency()->is($currency));
    }

    public function test_it_can_be_formatted_to_string_according_to_current_locale(): void
    {
        $currency = CurrencyFactory::new()->create(['code' => 'USD']);
        $money = new Money(100, $currency);

        static::assertSame('$1.00', $money->format());
    }

    public function test_it_can_be_formatted_to_string_according_to_new_locale(): void
    {
        $currency = CurrencyFactory::new()->create(['code' => 'USD']);
        $money = new Money(100, $currency);

        $this->app->setLocale('ru');

        static::assertSame("1,00\u{a0}\$", $money->format());
    }

    public function test_it_can_determine_major_units_amount(): void
    {
        $currency = CurrencyFactory::new()->create(['code' => 'USD', 'precision' => 3]);
        $money = new Money(3000, $currency);

        static::assertSame(3, $money->getMajorUnits());
    }

    public function test_it_calculates_major_units_correctly_if_precision_is_zero(): void
    {
        $currency = CurrencyFactory::new()->create(['code' => 'MRU', 'precision' => 0]);
        $money = new Money(3500, $currency);

        static::assertSame(3500, $money->getMajorUnits());
    }

    public function test_it_can_be_converted_into_string(): void
    {
        $money = new Money(100, CurrencyFactory::new()->create());

        static::assertSame($money->format(), (string) $money);
    }

    public function test_it_can_be_converted_into_money_with_another_currency(): void
    {
        $currencySource = CurrencyFactory::new()->create(['code' => 'USD', 'rate' => 1]);
        $currencyTarget = CurrencyFactory::new()->create(['code' => 'EUR', 'rate' => 3]);

        $money = new Money(100, $currencySource);

        static::assertSame(300, $money->convert($currencyTarget)->getAmount());
    }
}
