<?php

namespace Nevadskiy\Money\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Nevadskiy\Money\Casts\AsMoney;
use Nevadskiy\Money\Database\Factories\CurrencyFactory;
use Nevadskiy\Money\Models\Currency;
use Nevadskiy\Money\Models\CurrencyResolver;
use Nevadskiy\Money\Tests\TestCase;
use Nevadskiy\Money\Money;
use Ramsey\Uuid\Uuid as UuidFactory;

class CustomCurrencyModelTest extends TestCase
{
    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->createSchema();

        CurrencyResolver::use(CurrencyUuid::class);
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        $this->schema()->drop('products');
        $this->schema()->drop('currencies_uuid');

        CurrencyResolver::useDefault();

        parent::tearDown();
    }

    public function test_it_uses_custom_currency_model_to_handle_money(): void
    {
        $currency = CurrencyFactory::new()->default()->create();

        $product = new CustomCurrencyModelProduct();
        $product->cost = Money::fromMajorUnits(100, $currency);
        $product->save();

        static::assertInstanceOf(Money::class, $product->cost);
        static::assertInstanceOf(CurrencyUuid::class, $product->cost->getCurrency());
        static::assertSame(100, $product->cost->getMajorUnits());
        static::assertTrue($product->cost->getCurrency()->is($currency));
    }

    /**
     * Set up the database schema.
     */
    private function createSchema(): void
    {
        $this->schema()->create('currencies_uuid', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 3)->unique()->comment('ISO 4217');
            $table->string('name', 50);
            $table->string('symbol', 10)->nullable();
            $table->tinyInteger('scale');
            $table->float('rate')->default(1);
            $table->timestamps();
        });

        $this->schema()->create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('cost_amount')->unsigned();
            $table->foreignId('cost_currency_id')->constrained('currencies_uuid');
            $table->timestamps();
        });
    }
}

/**
 * @property Money cost
 */
class CurrencyUuid extends Currency
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'currencies_uuid';

    public static function booted(): void
    {
        static::creating(static function (self $model) {
            $model->setAttribute($model->getKeyName(), UuidFactory::uuid4());
        });
    }
}

/**
 * @property Money cost
 */
class CustomCurrencyModelProduct extends Model
{
    protected $table = 'products';

    protected $casts = [
        'cost' => AsMoney::class,
    ];
}
