<?php

declare(strict_types=1);

namespace Jeka\Money\Tests\Unit;

use Jeka\Money\Database\Factories\CurrencyFactory;
use Jeka\Money\Formatter\IntlFormatter;
use Jeka\Money\Money;
use Jeka\Money\Tests\TestCase;

class IntlFormatterTest extends TestCase
{
    /** @test */
    public function it_can_format_money_according_to_its_locale(): void
    {
        $currency = CurrencyFactory::new()->create(['code' => 'USD', 'precision' => 2]);
        $money = new Money(200, $currency);
        $formatter = new IntlFormatter('en');

        self::assertEquals('$2.00', $formatter->format($money));
    }

    /** @test */
    public function it_can_format_money_according_to_new_locale(): void
    {
        $currency = CurrencyFactory::new()->create(['code' => 'USD', 'precision' => 2]);
        $money = new Money(200, $currency);
        $formatter = new IntlFormatter('en');

        $formatter->setLocale('ru');

        self::assertEquals('2,00 $', $formatter->format($money));
    }
}
