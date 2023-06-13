<?php

namespace Nevadskiy\Money\Tests\Feature;

use Nevadskiy\Money\Money;
use Nevadskiy\Money\Scaler\RoundScaler;
use Nevadskiy\Money\Scaler\Scaler;
use Nevadskiy\Money\Tests\TestCase;

class MoneyTest extends TestCase
{
    // @todo exception that rate should be > 0
    // @todo exception that scale should be >= 0.

    public function test_it_can_immutably_set_new_amount_to_money(): void
    {
        $original = new Money(100);

        $money = $original->setAmount(0);

        static::assertSame(0, $money->getAmount());
        static::assertSame(100, $original->getAmount());
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

    public function test_it_can_be_serialized_to_json(): void
    {
        self::assertEquals('{"amount":1,"currency":"UAH"}', json_encode(new Money(100, 'UAH')));
    }

    public function test_it_can_be_immutably_multiplied(): void
    {
        $original = new Money(100);

        $multiplied = $original->multiply(0.5);

        static::assertSame(50, $multiplied->getAmount());
        static::assertSame(100, $original->getAmount());
    }

    public function test_it_can_be_immutably_divided(): void
    {
        $original = new Money(100);

        $divided = $original->divide(5);

        static::assertSame(20, $divided->getAmount());
        static::assertSame(100, $original->getAmount());
    }
}
