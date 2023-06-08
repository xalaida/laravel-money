<?php

namespace Nevadskiy\Money\Tests\Feature\Cast;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Nevadskiy\Money\Casts\AsMoney;
use Nevadskiy\Money\Database\Factories\CurrencyFactory;
use Nevadskiy\Money\Tests\TestCase;
use Nevadskiy\Money\Money;

class MoneyCastTest extends TestCase
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

    public function test_attribute_can_be_cast_to_money(): void
    {
        $currency = CurrencyFactory::new()->create();

        $product = new MoneyCastProduct();
        $product->cost = Money::fromMajorUnits(20, $currency);
        $product->save();

        static::assertInstanceOf(Money::class, $product->cost);
        static::assertSame(2000, $product->cost_amount);
        static::assertSame($currency->getKey(), $product->cost_currency_id);
    }

    /**
     * Set up the database schema.
     */
    private function createSchema(): void
    {
        $this->schema()->create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('cost_amount')->unsigned();
            $table->foreignId('cost_currency_id')->constrained('currencies');
            $table->timestamps();
        });
    }
}

/**
 * @property Money cost
 * @property int cost_amount
 * @property int cost_currency_id
 */
class MoneyCastProduct extends Model
{
    protected $table = 'products';

    protected $casts = [
        'cost' => AsMoney::class,
    ];
}

