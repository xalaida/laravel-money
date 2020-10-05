<?php

declare(strict_types=1);

namespace Jeka\Money\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Jeka\Money\Models\Currency;

class SeedCurrenciesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'money:currencies:seed {currencies?*} {--truncate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed currencies to the database';

    /**
     * Execute the console command.
     *
     * @return mixed|void
     */
    public function handle()
    {
        $this->truncateAttempt();

        foreach ($this->currencies() as $currency) {
            $this->seed($currency);
        }

        $this->info('Currencies have been seeded!');
    }

    /**
     * Try to truncate currencies if the action is requested.
     */
    private function truncateAttempt(): void
    {
        if (! $this->option('truncate')) {
            return;
        }

        if (! $this->confirmProductionTruncate()) {
            return;
        }

        $this->truncate();
    }

    /**
     * Truncate the currencies table.
     */
    private function truncate(): void
    {
        Currency::query()->truncate();
        $this->warn('Currencies have been truncated!');
    }

    /**
     * Confirm the production truncate process.
     */
    private function confirmProductionTruncate(): bool
    {
        if (! app()->environment('production')) {
            return true;
        }

        return $this->confirm('Are you sure you want to truncate the currencies table?');
    }

    /**
     * Get currencies to be seeded.
     */
    private function currencies(): array
    {
        $codes = $this->argument('currencies');

        if (empty($codes)) {
            return $this->allCurrencies();
        }

        return $this->getCurrenciesByCodes($codes);
    }

    /**
     * Get all currencies list.
     */
    protected function allCurrencies(): array
    {
        return require __DIR__.'/../../resources/currencies.php';
    }

    /**
     * Get currencies by the given codes.
     */
    private function getCurrenciesByCodes(array $codes): array
    {
        $codes = array_map(static function (string $code) {
            return Str::upper($code);
        }, $codes);

        return array_filter($this->allCurrencies(), static function (array $currency) use ($codes) {
            return in_array($currency['code'], $codes, true);
        });
    }

    /**
     * Seed the currency data.
     */
    private function seed(array $currency): void
    {
        Currency::query()->updateOrCreate(['code' => $currency['code']], $currency);
        $this->line("Currency {$currency['code']} has been seeded!");
    }
}
