<?php

namespace Nevadskiy\Money\Tests\Unit;

use Nevadskiy\Money\Money;
use Nevadskiy\Money\Tests\TestCase;

class CreateTest extends TestCase
{
    public function test_it_can_be_created_with_amount_and_currency(): void
    {
        $money = new Money(100, 'USD');

        static::assertSame(100, $money->getAmount());
        static::assertSame('USD', $money->getCurrency());
    }

    public function test_it_can_be_created_with_default_currency(): void
    {
        Money::setDefaultCurrency('UAH');

        $money = new Money(100);

        static::assertSame(100, $money->getAmount());
        static::assertSame('UAH', $money->getCurrency());
    }

    public function test_it_can_be_created_with_default_currency_from_minor_units(): void
    {
        Money::setDefaultCurrency('UAH');

        $money = Money::fromMinorUnits(100);

        static::assertSame(100, $money->getAmount());
        static::assertSame('UAH', $money->getCurrency());
    }

    public function test_it_can_be_created_with_default_currency_from_major_units(): void
    {
        Money::setDefaultCurrency('UAH');

        $money = Money::fromMajorUnits(1);

        static::assertSame(100, $money->getAmount());
        static::assertSame('UAH', $money->getCurrency());
    }

    public function test_it_can_be_created_with_default_zero_money(): void
    {
        Money::setDefaultCurrency('UAH');

        $money = Money::zero();

        static::assertSame(0, $money->getAmount());
        static::assertSame('UAH', $money->getCurrency());
    }
}
