<?php

namespace Nevadskiy\Money\Tests\Unit\Models;

use Nevadskiy\Money\Database\Factories\CurrencyFactory;
use Nevadskiy\Money\Tests\TestCase;

class CurrencyTest extends TestCase
{
    public function test_it_stores_code_in_uppercase(): void
    {
        $currency = CurrencyFactory::new()->create(['code' => 'usd']);

        self::assertSame('USD', $currency->code);
    }
}
