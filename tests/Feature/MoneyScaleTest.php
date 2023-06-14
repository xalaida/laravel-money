<?php

namespace Nevadskiy\Money\Tests\Feature;

use Nevadskiy\Money\Exceptions\CurrencyScaleMissingException;
use Nevadskiy\Money\Money;
use Nevadskiy\Money\Scaler\RoundScaler;
use Nevadskiy\Money\Scaler\Scaler;
use Nevadskiy\Money\Tests\TestCase;

class MoneyScaleTest extends TestCase
{
    public function test_it_uses_minor_amount_when_scale_is_unknown(): void
    {
        $money = new Money(150, 'XXX');

        static::assertSame(150, $money->getAmount());
    }

    public function test_it_throws_exception_when_major_units_scale_is_unknown(): void
    {
        $money = new Money(150, 'XXX');

        $this->expectException(CurrencyScaleMissingException::class);

        static::assertSame(150, $money->getMajorUnits());
    }

    public function test_it_throws_exception_when_creating_from_unknown_major_units_scale(): void
    {
        $this->expectException(CurrencyScaleMissingException::class);

        Money::fromMajorUnits(150, 'XXX');
    }

    public function test_it_returns_major_units_amount(): void
    {
        $this->app->instance(Scaler::class, new RoundScaler(['ABC' => 3]));

        $money = new Money(3000, 'ABC');

        static::assertSame(3, $money->getMajorUnits());
    }

    public function test_it_returns_major_units_correctly_if_scale_is_zero(): void
    {
        $this->app->instance(Scaler::class, new RoundScaler(['ABC' => 0]));

        $money = new Money(150, 'ABC');

        static::assertSame(150, $money->getMajorUnits());
    }
}
