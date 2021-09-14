<?php

namespace Nevadskiy\Money\Tests\Feature;

use Nevadskiy\Money\Database\Factories\CurrencyFactory;
use Nevadskiy\Money\Tests\Support\Models\Product;
use Nevadskiy\Money\Tests\TestCase;
use Nevadskiy\Money\ValueObjects\Money;

class MoneyCastTest extends TestCase
{
    public function test_money_attributes_can_be_saved_using_money_cast(): void
    {
        $currency = CurrencyFactory::new()->create();

        $product = new Product(['name' => 'Sony PlayStation 5']);
        $product->price = new Money(49900, $currency);
        $product->save();

        static::assertSame(49900, $product->price_amount);
        static::assertSame($currency->id, $product->price_currency_id);
    }

    public function test_money_attributes_can_be_casted_into_money_instance(): void
    {
        $currency = CurrencyFactory::new()->create();

        $product = new Product(['name' => 'Sony PlayStation 5']);
        $product->price = new Money(49900, $currency);
        $product->save();

        static::assertInstanceOf(Money::class, $product->fresh()->price);
    }
}
