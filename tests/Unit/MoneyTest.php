<?php

namespace Nevadskiy\Money\Tests\Unit;

use Nevadskiy\Money\Converter\Converter;
use Nevadskiy\Money\Database\Factories\CurrencyFactory;
use Nevadskiy\Money\Money;
use Nevadskiy\Money\Registry\CurrencyRegistry;
use Nevadskiy\Money\Tests\TestCase;

class MoneyTest extends TestCase
{
    // @todo exception that rate should be > 0
    // @todo exception that scale should be >= 0.

    public function test_it_can_be_instantiated_with_amount_and_currency(): void
    {
        $money = new Money(100, 'USD');

        static::assertSame(100, $money->getAmount());
        static::assertSame('USD', $money->getCurrency());
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
        $this->app->get(CurrencyRegistry::class)->set('ABC', [
            'scale' => 3
        ]);

        $money = new Money(3000, 'ABC');

        static::assertSame(3, $money->getMajorUnits());
    }

    public function test_it_calculates_major_units_correctly_if_scale_is_zero(): void
    {
        $this->app->get(CurrencyRegistry::class)->set('ABC', [
            'scale' => 0,
        ]);

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

    // @todo conversions...

    public function test_it_can_be_converted_into_money_with_another_currency_with_big_scale(): void
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

    public function test_it_can_be_converted_to_another_currency_without_scale(): void
    {
        $this->app->get(CurrencyRegistry::class)->set('UAH', [
            'rate' => 36.916908,
            'scale' => 2,
        ]);

        $this->app->get(CurrencyRegistry::class)->set('BTC', [
            'rate' => 1 / 25000,
            'scale' => 0,
        ]);

        $money = new Money(1, 'BTC');

        static::assertSame(92292270, $money->convert('UAH')->getAmount());
    }

    public function test_it_can_be_converted_to_default_currency(): void
    {
        Money::setDefaultCurrency('USD');

        $this->app->get(CurrencyRegistry::class)->set('USD', [
            'rate' => 1,
            'scale' => 2,
        ]);

        $this->app->get(CurrencyRegistry::class)->set('BTC', [
            'rate' => 1 / 25000,
            'scale' => 0,
        ]);

        $money = new Money(1, 'BTC');

        static::assertSame(2500000, $money->convert()->getAmount());
    }

    public function test_it_can_be_converted_to_another_scale_currency(): void
    {
        Money::setDefaultCurrency('USD');

        $this->app->get(CurrencyRegistry::class)->set('USD', [
            'rate' => 1.0,
            'scale' => 2,
        ]);

        $this->app->get(CurrencyRegistry::class)->set('JPY', [
            'rate' => 139.395,
            'scale' => 0,
        ]);

        $money = new Money(50, 'USD');

        static::assertSame(69, $money->convert('JPY')->getAmount());
    }

    public function test_it_can_be_converted_to_another_currency(): void
    {
        Money::setDefaultCurrency('USD');

        $this->app->get(CurrencyRegistry::class)->set('USD', [
            'rate' => 1.0,
            'scale' => 2,
        ]);

        $this->app->get(CurrencyRegistry::class)->set('JPY', [
            'rate' => 139.395,
            'scale' => 0,
        ]);

        $money = new Money(1, 'USD');

        static::assertSame(139, $money->convert('JPY')->getAmount());
    }

    public function test_it_can_be_created_with_default_currency(): void
    {
        Money::setDefaultCurrency('UAH');

        $money = new Money(100);

        static::assertSame(100, $money->getAmount());
        static::assertSame('UAH', $money->getCurrency());
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
