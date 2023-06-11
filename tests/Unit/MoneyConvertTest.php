<?php

namespace Nevadskiy\Money\Tests\Unit;

use Nevadskiy\Money\Money;
use Nevadskiy\Money\Registry\CurrencyRegistry;
use Nevadskiy\Money\Tests\TestCase;

class MoneyConvertTest extends TestCase
{
    public function test_it_can_be_converted_to_another_currency(): void
    {
        $this->app->get(CurrencyRegistry::class)->set('USD', [
            'rate' => 1.0,
            'scale' => 2,
        ]);

        $this->app->get(CurrencyRegistry::class)->set('UAH', [
            'rate' => 36.916908,
            'scale' => 2,
        ]);

        $money = Money::fromMajorUnits(100, 'USD')->convert('UAH');

        static::assertSame(369169, $money->getAmount());
        static::assertSame('UAH', $money->getCurrency());
    }

    public function test_it_can_be_converted_to_default_currency(): void
    {
        Money::setDefaultCurrency('USD');

        $this->app->get(CurrencyRegistry::class)->set('USD', [
            'rate' => 1.0,
            'scale' => 2,
        ]);

        $this->app->get(CurrencyRegistry::class)->set('UAH', [
            'rate' => 36.916908,
            'scale' => 2,
        ]);

        $money = Money::fromMajorUnits(100, 'UAH')->convert();

        static::assertSame(270, $money->getAmount());
        static::assertSame('USD', $money->getCurrency());
    }

    public function test_it_can_be_converted_into_money_to_another_currency_with_big_scale(): void
    {
        $this->app->get(CurrencyRegistry::class)->set('UAH', [
            'rate' => 36.916908,
            'scale' => 2,
        ]);

        $this->app->get(CurrencyRegistry::class)->set('BTC', [
            'rate' => 1 / 25000,
            'scale' => 8,
        ]);

        $money = Money::fromMajorUnits(1, 'BTC');

        static::assertSame(92292270, $money->convert('UAH')->getAmount());
    }

    public function test_it_can_be_converted_to_another_currency_with_zero_scale(): void
    {
        $this->app->get(CurrencyRegistry::class)->set('USD', [
            'rate' => 1.0,
            'scale' => 2,
        ]);

        $this->app->get(CurrencyRegistry::class)->set('JPY', [
            'rate' => 139.395,
            'scale' => 0,
        ]);

        $money = new Money(50, 'USD');

        static::assertSame(70, $money->convert('JPY')->getAmount());
    }
}
