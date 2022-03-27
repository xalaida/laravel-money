<?php

namespace Nevadskiy\Money\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Collection;
use Nevadskiy\Money\Events\CurrencyRateUpdated;
use Nevadskiy\Money\Models\Currency;
use Nevadskiy\Money\Models\CurrencyResolver;
use Nevadskiy\Money\RateProvider\RateProvider;
use Nevadskiy\Money\ValueObjects\Rate;

class UpdateCurrencyRatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currencies:rates:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update rates for currencies';

    /**
     * The rate provider instance.
     *
     * @var RateProvider
     */
    protected $provider;

    /**
     * The event dispatcher instance.
     *
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * Init the command instance.
     */
    public function init(RateProvider $provider, Dispatcher $dispatcher): void
    {
        $this->provider = $provider;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Execute the console command.
     */
    public function handle(RateProvider $provider, Dispatcher $dispatcher): void
    {
        $this->init($provider, $dispatcher);

        $rates = $this->provider->getRates();

        foreach ($this->currencies($rates) as $currency) {
            $this->updateRate($currency, $rates[$currency->code]);
        }

        $this->info('Currency rates have been updated!');
    }

    /**
     * Get currencies by rates collection.
     */
    protected function currencies(array $rates): Collection
    {
        return CurrencyResolver::resolve()
            ->newQuery()
            // TODO: refactor array_keys to use another format. introduce better provider rates structure.
            ->whereIn('code', array_keys($rates))
            ->get();
    }

    /**
     * Update the rate for the given currency.
     */
    protected function updateRate(Currency $currency, Rate $rate): void
    {
        $currency->rate = $rate;
        $currency->save();

        $this->dispatcher->dispatch(new CurrencyRateUpdated($currency));

        $this->line("Rate has been updated for the currency {$currency->code} with the value {$rate->getValue()}");
    }
}
