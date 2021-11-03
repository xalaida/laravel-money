<?php

namespace Nevadskiy\Money\Tests\Unit\ValueObjects;

use Nevadskiy\Money\Converter\Converter;
use Nevadskiy\Money\Database\Factories\CurrencyFactory;
use Nevadskiy\Money\Tests\TestCase;
use Nevadskiy\Money\ValueObjects\Money;

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

    public function test_it_can_be_formatted_according_to_the_given_locale(): void
    {
        $money = new Money(100, CurrencyFactory::new()->create(['code' => 'USD']));

        static::assertSame("1,00\u{a0}\$", $money->format('ru'));
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
        $originalCurrency = CurrencyFactory::new()->rated(1)->create(['code' => 'USD']);
        $currency = CurrencyFactory::new()->rated(3)->create(['code' => 'EUR']);

        $money = new Money(100, $originalCurrency);

        static::assertSame(300, $money->convert($currency)->getAmount());
    }

    public function test_it_can_be_converted_using_default_converter_currency(): void
    {
        $originalCurrency = CurrencyFactory::new()->rated(1)->create(['code' => 'USD']);
        $originalMoney = Money::fromMajorUnits(100, $originalCurrency);

        $currency = CurrencyFactory::new()->rated(3)->create(['code' => 'EUR']);

        app(Converter::class)->setDefaultCurrency($currency);

        $money = $originalMoney->convert();

        static::assertSame(300, $money->getMajorUnits());
        static::assertTrue($money->getCurrency()->is($currency));
    }

    public function test_it_can_resolve_default_currency_using_given_resolver(): void
    {
        $currency = CurrencyFactory::new()->rated(1)->create(['code' => 'USD']);

        Money::resolveDefaultCurrencyUsing(function () use ($currency) {
            return $currency;
        });

        static::assertTrue(Money::getDefaultCurrency()->is($currency));
    }

    public function test_it_can_be_created_with_default_currency(): void
    {
        $currency = CurrencyFactory::new()->rated(1)->create(['code' => 'USD']);

        Money::resolveDefaultCurrencyUsing(function () use ($currency) {
            return $currency;
        });

        $money = Money::fromMinorUnits(1000);

        static::assertTrue($money->getCurrency()->is($currency));
        static::assertSame(1000, $money->getMinorUnits());
    }
}
