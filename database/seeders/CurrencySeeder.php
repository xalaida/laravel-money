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
