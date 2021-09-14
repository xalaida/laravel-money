<?php

namespace Nevadskiy\Money\Tests\Unit\Formatter;

use Nevadskiy\Money\Database\Factories\CurrencyFactory;
use Nevadskiy\Money\Formatter\IntlFormatter;
use Nevadskiy\Money\ValueObjects\Money;
use Nevadskiy\Money\Tests\TestCase;

class IntlFormatterTest extends TestCase
{
    public function test_it_can_format_money_according_to_its_locale(): void
    {
        $currency = CurrencyFactory::new()->create(['code' => 'USD', 'precision' => 2]);
        $money = new Money(200, $currency);
        $formatter = new IntlFormatter('en');

        static::assertSame('$2.00', $formatter->format($money));
    }

    public function test_it_can_format_money_according_to_new_locale(): void
    {
        $currency = CurrencyFactory::new()->create(['code' => 'USD', 'precision' => 2]);
        $money = new Money(200, $currency);
        $formatter = new IntlFormatter('en');

        $formatter->setLocale('ru');

        static::assertSame("2,00\u{a0}\$", $formatter->format($money));
    }
}
