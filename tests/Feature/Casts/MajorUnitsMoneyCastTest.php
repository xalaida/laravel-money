<?php

namespace Nevadskiy\Money\Tests\Feature\Casts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Nevadskiy\Money\Tests\TestCase;
use Nevadskiy\Money\Money;

class MajorUnitsMoneyCastTest extends TestCase
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

    /**
     * @test
     */
    public function attribute_can_be_cast_to_money_with_major_units(): void
    {
        $product = new MajorUnitsMoneyCastProduct();
        $product->cost = Money::fromMajorUnits(99.99, 'USD');
        $product->save();

        $this->assertDatabaseHas('products', [
            'cost' => 99.99,
        ]);

        $product->refresh();

        static::assertInstanceOf(Money::class, $product->cost);
        static::assertSame(9999, $product->cost->getAmount());
        static::assertSame(99.99, $product->cost->getMajorUnits());
        static::assertSame('USD', $product->cost->getCurrency());
    }

    /**
     * Set up the database schema.
     */
    protected function createSchema(): void
    {
        $this->schema()->create('products', function (Blueprint $table) {
            $table->id();
            $table->decimal('cost')->unsigned();
            $table->timestamps();
        });
    }
}

/**
 * @property Money cost
 */
class MajorUnitsMoneyCastProduct extends Model
{
    protected $table = 'products';

    protected $casts = [
        'cost' => Money::class.':USD,U',
    ];
}
