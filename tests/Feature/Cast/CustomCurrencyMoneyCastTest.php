<?php

namespace Nevadskiy\Money\Tests\Feature\Cast;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Nevadskiy\Money\Casts\AsMoney;
use Nevadskiy\Money\Tests\TestCase;
use Nevadskiy\Money\Money;

class CustomCurrencyMoneyCastTest extends TestCase
{
    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->createSchema();
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        $this->schema()->drop('products');

        parent::tearDown();
    }

    // @todo test when currency mismatch (default with money)

    /**
     * @test
     */
    public function attribute_can_be_cast_to_money_with_custom_currency(): void
    {
        Money::setDefaultCurrency('USD');

        $product = new CustomCurrencyMoneyCastProduct();
        $product->cost = new Money(100, 'UAH');
        $product->save();

        $product->refresh();

        static::assertInstanceOf(Money::class, $product->cost);
        static::assertSame(100, $product->cost->getAmount());
        static::assertSame('UAH', $product->cost->getCurrency());
    }

    /**
     * Set up the database schema.
     */
    private function createSchema(): void
    {
        $this->schema()->create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('cost')->unsigned();
            $table->timestamps();
        });
    }
}

/**
 * @property Money cost
 */
class CustomCurrencyMoneyCastProduct extends Model
{
    protected $table = 'products';

    protected $casts = [
        'cost' => AsMoney::class.':UAH',
    ];
}