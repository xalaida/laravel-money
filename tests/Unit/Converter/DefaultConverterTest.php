<?php

namespace Nevadskiy\Money\Tests\Unit\Converter;

use Nevadskiy\Money\Converter\DefaultConverter;
use Nevadskiy\Money\Database\Factories\CurrencyFactory;
use Nevadskiy\Money\Exceptions\DefaultCurrencyMissingException;
use Nevadskiy\Money\Tests\TestCase;
use Nevadskiy\Money\ValueObjects\Money;

class DefaultConverterTest extends TestCase
{
    public function test_it_can_convert_money_according_to_default_currency(): void
    {
        $defaultCurrency = CurrencyFactory::new()->rated(1)->create([
            'code' => 'EUR',
        ]);

        $originalCurrency = CurrencyFactory::new()->rated(2)->create([
            'code' => 'USD',
        ]);

        $converter = new DefaultConverter($defaultCurrency);

        $money = $converter->convert(Money::fromMajorUnits(100, $originalCurrency));

        static::assertTrue($money->getCurrency()->is($defaultCurrency));
        static::assertSame($money->getMajorUnits(), 50);
    }

    public function test_it_can_convert_money_according_to_updated_default_currency(): void
    {
        $defaultCurrency = CurrencyFactory::new()->rated(1)->create(['code' => 'EUR']);
        $money = Money::fromMajorUnits(100, $defaultCurrency);

        $currency = CurrencyFactory::new()->rated(2)->create(['code' => 'USD']);

        $converter = new DefaultConverter($defaultCurrency);
        $converter->setDefaultCurrency($currency);
        $money = $converter->convert($money);

        static::assertTrue($money->getCurrency()->is($currency));
        static::assertSame($money->getMajorUnits(), 200);
    }

    public function test_it_can_convert_money_according_to_given_currency(): void
    {
        $originalCurrency = CurrencyFactory::new()->rated(2)->create(['code' => 'USD']);
        $money = Money::fromMajorUnits(100, $originalCurrency);

        $currency = CurrencyFactory::new()->rated(1)->create(['code' => 'EUR']);

        $converter = new DefaultConverter();
        $money = $converter->convert($money, $currency);

        static::assertTrue($money->getCurrency()->is($currency));
        static::assertSame($money->getMajorUnits(), 50);
    }

    public function test_it_can_convert_money_to_same_currency(): void
    {
        $currency = CurrencyFactory::new()->rated(2)->create(['code' => 'USD']);
        $originalMoney = Money::fromMajorUnits(100, $currency);

        $converter = new DefaultConverter();
        $money = $converter->convert($originalMoney, $currency);

        static::assertNotSame($originalMoney, $money);
        static::assertTrue($money->getCurrency()->is($currency));
        static::assertSame($money->getMajorUnits(), 100);
    }

    public function test_it_throws_an_exception_when_no_default_currency_is_set(): void
    {
        $originalCurrency = CurrencyFactory::new()->unrated()->create([
            'code' => 'USD',
        ]);

        $converter = new DefaultConverter();

        $this->expectException(DefaultCurrencyMissingException::class);

        $converter->convert(Money::fromMajorUnits(100, $originalCurrency));
    }
}
