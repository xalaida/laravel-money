<?php

namespace Nevadskiy\Money\Tests\Feature\Casts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Nevadskiy\Money\Tests\TestCase;
use Nevadskiy\Money\Money;

class DefaultZeroMoneyCastTest extends TestCase
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
    public function it_can_cast_nullable_cost_to_zero_money(): void
    {
        $result = DefaultZeroMoneyCastProduct::query()
            ->withCasts(['total' => Money::class.':USD,u,0'])
            ->select(new Expression('SUM(cost) as total'))
            ->first();

        static::assertInstanceOf(Money::class, $result->total);
        static::assertSame(0, $result->total->getAmount());
        static::assertSame('USD', $result->total->getCurrency());
    }

    /**
     * @test
     */
    public function it_can_cast_cost_to_using_dynamic_cast(): void
    {
        DefaultZeroMoneyCastProduct::create(['cost' => 500]);
        DefaultZeroMoneyCastProduct::create(['cost' => 1000]);
        DefaultZeroMoneyCastProduct::create(['cost' => 2000]);

        $result = DefaultZeroMoneyCastProduct::query()
            ->withCasts(['total' => Money::class.':USD,u,0'])
            ->select(new Expression('SUM(cost) as total'))
            ->first();

        static::assertInstanceOf(Money::class, $result->total);
        static::assertSame(3500, $result->total->getAmount());
        static::assertSame('USD', $result->total->getCurrency());
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

class DefaultZeroMoneyCastProduct extends Model
{
    protected $table = 'products';
}
