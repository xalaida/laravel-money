<?php

namespace Nevadskiy\Money\Tests\Feature;

use Nevadskiy\Money\Money;
use Nevadskiy\Money\RateProvider\ArrayRateProvider;
use Nevadskiy\Money\RateProvider\RateProvider;
use Nevadskiy\Money\Scaler\RoundScaler;
use Nevadskiy\Money\Scaler\Scaler;
use Nevadskiy\Money\Tests\TestCase;

class ConvertTest extends TestCase
{
    public function test_it_can_be_converted_to_another_currency(): void
    {
        $this->app->instance(RateProvider::class, new ArrayRateProvider([
            'USD' => 1.0,
            'UAH' => 36.916908,
        ]));

        $money = Money::fromMajorUnits(100, 'USD')->convert('UAH');

        static::assertSame(369169, $money->getAmount());
        static::assertSame('UAH', $money->getCurrency());
    }

    public function test_it_can_be_converted_to_default_currency(): void
    {
        Money::setDefaultCurrency('USD');

        $this->app->instance(RateProvider::class, new ArrayRateProvider([
            'USD' => 1.0,
            'UAH' => 36.916908,
        ]));

        $money = Money::fromMajorUnits(100, 'UAH')->convert();

        static::assertSame(270, $money->getAmount());
        static::assertSame('USD', $money->getCurrency());
    }

    public function test_it_can_be_converted_into_money_to_another_currency_with_big_scale(): void
    {
        $this->app->instance(RateProvider::class, new ArrayRateProvider([
            'UAH' => 36.916908,
            'BTC' => 1 / 25000,
        ]));

        $this->app->instance(Scaler::class, new RoundScaler([
            'UAH' => 2,
            'BTC' => 8,
        ]));

        $money = Money::fromMajorUnits(1, 'BTC');

        static::assertSame(92292270, $money->convert('UAH')->getAmount());
    }

    public function test_it_can_be_converted_to_another_currency_with_zero_scale(): void
    {
        $this->app->instance(RateProvider::class, new ArrayRateProvider([
            'USD' => 1.0,
            'JPY' => 139.395,
        ]));

        $this->app->instance(Scaler::class, new RoundScaler([
            'USD' => 2,
            'JPY' => 0,
        ]));

        $money = new Money(50, 'USD');

        static::assertSame(69, $money->convert('JPY')->getAmount());
    }
}
