<?php

namespace Nevadskiy\Money\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Nevadskiy\Money\Models\Currency;
use Nevadskiy\Money\ValueObjects\Rate;

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
            'rate' => new Rate($this->faker->randomFloat(0.5, 5.0)),
        ];
    }

    /**
     * Make an unrated currency.
     */
    public function unrated(): CurrencyFactory
    {
        return $this->rated(1);
    }

    /**
     * Make a rated currency.
     */
    public function rated(float $rate): CurrencyFactory
    {
        return $this->state([
            'precision' => 2,
            'rate' => new Rate($rate),
        ]);
    }

    /**
     * Fill the USD state.
     * TODO: remove.
     * @deprecated make it unique. rename into 'static' or something that indicates rate=1
     */
    public function usd(): self
    {
        return $this->state([
            'code' => 'USD',
            'name' => 'United States dollar',
            'symbol' => '$',
            'precision' => 2,
            'rate' => new Rate(1),
        ]);
    }
}
