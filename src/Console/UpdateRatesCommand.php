<?php

declare(strict_types=1);

namespace Nevadskiy\Money\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Collection;
use Nevadskiy\Money\Events\CurrencyRateUpdated;
use Nevadskiy\Money\Models\Currency;
use Nevadskiy\Money\RateProvider\Rate;
use Nevadskiy\Money\RateProvider\RateProvider;

class UpdateRatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'money:rates:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update rates for currencies';

    /**
     * @var RateProvider
     */
    private $provider;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Create a new command instance.
     */
    public function __construct(RateProvider $provider, Dispatcher $dispatcher)
    {
        parent::__construct();
        $this->provider = $provider;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $rates = $this->provider->getRates()->mapByCodes();

        foreach ($this->currencies($rates) as $currency) {
            $this->updateRate($currency, $rates[$currency->code]);
        }

        $this->info('Currency rates have been updated!');
    }

    /**
     * Get currencies by rates collection.
     */
    private function currencies(array $rates): Collection
    {
        return Currency::whereIn('code', array_keys($rates))->get();
    }

    /**
     * Update the rate for the given currency.
     */
    private function updateRate(Currency $currency, Rate $rate): void
    {
        $currency->updateRate($rate->getValue());

        $this->dispatcher->dispatch(new CurrencyRateUpdated($currency));

        $this->line("Rate has been updated for the currency {$rate->getCode()} with the value {$rate->getValue()}");
    }
}
