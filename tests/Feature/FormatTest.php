<?php

namespace Nevadskiy\Money\Tests\Feature;

use Nevadskiy\Money\Money;
use Nevadskiy\Money\Tests\TestCase;

class FormatTest extends TestCase
{
    public function test_it_can_be_formatted_according_to_default_application_locale(): void
    {
        static::assertSame('1,00 USD', (new Money(100, 'USD'))->format());
    }

    public function test_it_can_be_formatted_according_to_current_application_locale(): void
    {
        $money = new Money(100, 'UAH');

        $this->app->setLocale('en');

        static::assertSame("UAH 1.00", $money->format());
    }

    public function test_it_can_be_formatted_according_to_given_locale(): void
    {
        $money = new Money(100, 'UAH');

        static::assertSame("UAH 1.00", $money->format('en'));
    }

    public function test_it_can_be_converted_into_string(): void
    {
        static::assertSame("1,00 ₴", (string) new Money(100));
    }
}
