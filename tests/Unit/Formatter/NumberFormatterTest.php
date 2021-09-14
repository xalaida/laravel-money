<?php

namespace Nevadskiy\Money\Tests\Unit\Formatter;

use Nevadskiy\Money\Database\Factories\CurrencyFactory;
use Nevadskiy\Money\Formatter\DefaultFormatter;
use Nevadskiy\Money\ValueObjects\Money;
use Nevadskiy\Money\Tests\TestCase;

class NumberFormatterTest extends TestCase
{
    public function test_it_can_format_money_according_to_given_locale(): void
    {
        $currency = CurrencyFactory::new()->unrated()->create(['code' => 'USD']);
        $money = Money::fromMajorUnits(200, $currency);

        $formatter = new DefaultFormatter('ru');

        static::assertSame('$200.00', $formatter->format($money, 'en'));
    }

    public function test_it_can_format_money_according_to_default_locale(): void
    {
        $currency = CurrencyFactory::new()->unrated()->create(['code' => 'USD']);
        $money = Money::fromMajorUnits(200, $currency);
        $formatter = new DefaultFormatter('ru');

        self::assertEquals('200,00 $', $formatter->format($money));
    }

    public function test_it_can_format_money_according_to_updated_default_locale(): void
    {
        $currency = CurrencyFactory::new()->unrated()->create(['code' => 'USD']);
        $money = Money::fromMajorUnits(200, $currency);

        $formatter = new DefaultFormatter('en');
        $formatter->setDefaultLocale('ru');

        static::assertSame('200,00 $', $formatter->format($money));
    }
}
