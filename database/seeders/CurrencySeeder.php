<?php

declare(strict_types=1);

namespace Jeka\Money\Database\Seeders;

use Illuminate\Database\Seeder;
use Jeka\Money\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Currency::query()->truncate();

        foreach ($this->currencies() as $category) {
            Currency::query()->create($category);
        }
    }

    /**
     * Default currencies to be seeded.
     * TODO: refactor using config or CLI command for seeding
     * TODO: extract currencies into resources/currencies.php file.
     *
     * @return string[]
     */
    protected function currencies(): array
    {
        return [
            [
                'code' => 'USD',
                'precision' => 2,
            ],
            [
                'code' => 'EUR',
                'precision' => 2,
            ],
        ];
    }
}
