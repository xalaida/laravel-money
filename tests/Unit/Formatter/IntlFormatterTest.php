<?php

namespace Nevadskiy\Money\Tests\Unit\Formatter;

use Nevadskiy\Money\Formatter\IntlFormatter;
use Nevadskiy\Money\Money;
use Nevadskiy\Money\Tests\TestCase;

class IntlFormatterTest extends TestCase
{
    public function test_it_can_be_formatted_according_to_locale(): void
    {
        $money = Money::fromMajorUnits(200, 'USD');

        $formatter = new IntlFormatter();

        static::assertSame('$200.00', $formatter->format($money, 'en'));
    }

    public function test_it_can_format_money_according_to_default_locale(): void
    {
        $money = Money::fromMajorUnits(200, 'USD');

        $formatter = new IntlFormatter();

        static::assertSame("200,00 USD", $formatter->format($money, 'uk'));
    }
}
