<?php

namespace Nevadskiy\Money\Tests\Unit\Converter;

use Nevadskiy\Money\Converter\DefaultConverter;
use Nevadskiy\Money\Database\Factories\CurrencyFactory;
use Nevadskiy\Money\Money;
use Nevadskiy\Money\Tests\TestCase;

class DefaultConverterTest extends TestCase
{
    public function test_it_can_convert_money_according_to_default_currency(): void
    {
        $defaultCurrency = CurrencyFactory::new()->create([
            'code' => 'EUR',
            'rate' => 1,
        ]);

        $originalCurrency = CurrencyFactory::new()->create([
            'code' => 'USD',
            'rate' => 2,
        ]);

        $converter = new DefaultConverter($defaultCurrency);

        $money = $converter->convert(Money::fromMajorUnits(100, $originalCurrency));

        self::assertTrue($money->getCurrency()->is($defaultCurrency));
        self::assertSame($money->getMajorUnits(), 50);
    }
}
