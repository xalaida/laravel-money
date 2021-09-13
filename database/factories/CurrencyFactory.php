<?php

namespace Nevadskiy\Money\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Nevadskiy\Money\Models\Currency;

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
            'name' => $this->faker->word,
            'symbol' => $this->faker->randomElement(['$', '€', '£']),
            'precision' => 2,
            'rate' => $this->faker->randomFloat(),
        ];
    }

    /**
     * Fill the USD state.
     * @deprecated make it unique. rename into 'static' or something that indicates rate=1
     */
    public function usd(): self
    {
        return $this->state([
            'code' => 'USD',
            'name' => 'United States dollar',
            'symbol' => '$',
            'precision' => 2,
            'rate' => 1,
        ]);
    }
}
