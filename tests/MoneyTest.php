<?php

namespace Nevadskiy\Money\Tests;

use Nevadskiy\Money\Money;
use Nevadskiy\Money\Scaler\ArrayScaler;
use Nevadskiy\Money\Scaler\Scaler;

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

    public function test_it_can_be_formatted_to_string_according_to_current_locale(): void
    {
        static::assertSame('$1.00', (new Money(100, 'USD'))->format());
    }

    public function test_it_can_be_formatted_to_string_according_to_given_locale(): void
    {
        $money = new Money(100, 'UAH');

        $this->app->setLocale('uk');

        static::assertSame("1,00 ₴", $money->format());
    }

    public function test_it_can_be_formatted_according_to_the_given_locale(): void
    {
        $money = new Money(100, 'UAH');

        static::assertSame("1,00 ₴", $money->format('uk'));
    }

    public function test_it_can_determine_major_units_amount(): void
    {
        $this->app->instance(Scaler::class, new ArrayScaler(['ABC' => 3]));

        $money = new Money(3000, 'ABC');

        static::assertSame(3, $money->getMajorUnits());
    }

    public function test_it_calculates_major_units_correctly_if_scale_is_zero(): void
    {
        $this->app->instance(Scaler::class, new ArrayScaler(['ABC' => 0]));

        $money = new Money(150, 'ABC');

        static::assertSame(150, $money->getMajorUnits());
    }

    public function test_it_can_be_converted_into_string(): void
    {
        static::assertSame("UAH 1.00", (string) new Money(100));
    }

    public function test_it_can_be_serialized_to_json(): void
    {
        self::assertEquals('{"amount":100,"currency":"UAH"}', json_encode(new Money(100, 'UAH')));
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
