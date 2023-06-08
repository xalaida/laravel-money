<?php

namespace Nevadskiy\Money\Tests\Feature\Cast;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Nevadskiy\Money\Casts\AsMoneyOf;
use Nevadskiy\Money\Database\Factories\CurrencyFactory;
use Nevadskiy\Money\Tests\TestCase;
use Nevadskiy\Money\Money;

class MoneyOfCastTest extends TestCase
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

    public function test_attribute_can_be_cast_to_money_with_specified_currency(): void
    {
        $currency = CurrencyFactory::new()->create(['code' => 'UAH']);

        $product = new MoneyOfCastProduct();
        $product->cost = Money::fromMajorUnits(20, $currency);
        $product->save();

        static::assertInstanceOf(Money::class, $product->cost);
        static::assertSame(2000, $product->cost->getAmount());
        static::assertSame('UAH', $product->cost->getCurrency()->getCode());
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
class MoneyOfCastProduct extends Model
{
    protected $table = 'products';

    protected $casts = [
        'cost' => AsMoneyOf::class.':UAH',
    ];
}
