<?php

namespace Nevadskiy\Money\Tests\Feature;

use Nevadskiy\Money\Money;
use Nevadskiy\Money\Tests\TestCase;

class MoneyTest extends TestCase
{
    public function test_it_can_immutably_set_new_amount_to_money(): void
    {
        $original = new Money(100);

        $money = $original->setAmount(0);

        static::assertSame(0, $money->getAmount());
        static::assertSame(100, $original->getAmount());
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

    public function test_it_can_be_serialized_to_json(): void
    {
        self::assertEquals('{"amount":1,"currency":"UAH"}', json_encode(new Money(100, 'UAH')));
    }
}
