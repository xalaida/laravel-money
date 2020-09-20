<?php

declare(strict_types=1);

namespace Jeka\Money\Tests\Unit;

use Jeka\Money\Database\Factories\CurrencyFactory;
use Jeka\Money\Money;
use Jeka\Money\Tests\TestCase;

class MoneyTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated_with_amount_in_subunits_and_currency(): void
    {
        $currency = CurrencyFactory::new()->create();

        $money = new Money(100, $currency);

        self::assertEquals(100, $money->getAmount());
        self::assertTrue($money->getCurrency()->is($currency));
    }
}
