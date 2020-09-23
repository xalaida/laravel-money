<?php

declare(strict_types=1);

namespace Jeka\Money\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jeka\Money\Models\Currency;

class CurrencyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Currency::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->currencyCode,
            'precision' => 2,
            'rate' => 1,
        ];
    }

    /**
     * Create the USD currency.
     */
    public static function USD(): Currency
    {
        return static::new()->create([
            'code' => 'USD',
            'precision' => 2,
        ]);
    }
}
