<?php

namespace Nevadskiy\Money\Tests\Unit\Converter;

use Nevadskiy\Money\Converter\DefaultConverter;
use Nevadskiy\Money\Database\Factories\CurrencyFactory;
use Nevadskiy\Money\Exceptions\DefaultCurrencyMissingException;
use Nevadskiy\Money\ValueObjects\Money;
use Nevadskiy\Money\Tests\TestCase;

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

        self::assertTrue($money->getCurrency()->is($defaultCurrency));
        self::assertSame($money->getMajorUnits(), 50);
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
