<?php

namespace Nevadskiy\Money\Tests\Feature\Cast;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Nevadskiy\Money\Casts\AsDefaultMoney;
use Nevadskiy\Money\Database\Factories\CurrencyFactory;
use Nevadskiy\Money\Tests\TestCase;
use Nevadskiy\Money\ValueObjects\Money;

class MoneyCastToDefaultCurrencyTest extends TestCase
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

    public function test_attribute_can_be_cast_using_default_currency(): void
    {
        $defaultCurrency = CurrencyFactory::new()->default()->create();
        $anotherCurrency = CurrencyFactory::new()->create();

        $product = new DefaultMoneyCastProduct();
        $product->cost = Money::fromMajorUnits(50);
        $product->save();

        static::assertInstanceOf(Money::class, $product->cost);
        static::assertSame(50, $product->cost->getMajorUnits());
        static::assertTrue($product->cost->getCurrency()->is($defaultCurrency));
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        $this->schema()->drop('products');

        parent::tearDown();
    }
}

/**
 * @property Money cost
 */
class DefaultMoneyCastProduct extends Model
{
    protected $table = 'products';

    protected $casts = [
        'cost' => AsDefaultMoney::class,
    ];
}
