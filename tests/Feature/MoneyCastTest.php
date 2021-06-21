<?php

declare(strict_types=1);

namespace Nevadskiy\Money\Tests\Unit;

use Nevadskiy\Money\Database\Factories\CurrencyFactory;
use Nevadskiy\Money\Money;
use Nevadskiy\Money\Tests\Support\Models\Product;
use Nevadskiy\Money\Tests\TestCase;

class MoneyCastTest extends TestCase
{
    /** @test */
    public function money_attributes_can_be_saved_using_money_cast(): void
    {
        $usd = CurrencyFactory::USD();
        $product = new Product(['name' => 'Sony PlayStation 5']);

        $product->price = new Money(49900, $usd);

        $product->save();

        self::assertEquals(49900, $product->price_amount);
        self::assertEquals($usd->id, $product->price_currency_id);
    }
}
