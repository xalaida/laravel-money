<?php

namespace Nevadskiy\Money\Database\Seeders;

use Illuminate\Database\Seeder;
use Nevadskiy\Money\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->currencies() as $currency) {
            Currency::query()->updateOrCreate(['code' => $currency['code']], $currency);
        }
    }

    /**
     * Get currencies list.
     */
    protected function currencies(): array
    {
        return require __DIR__.'/../../resources/currencies.php';
    }
}
