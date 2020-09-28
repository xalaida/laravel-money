<?php

namespace Jeka\Money\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Collection;
use Jeka\Money\Events\CurrencyRateUpdated;
use Jeka\Money\Models\Currency;
use Jeka\Money\RateProvider\Rate;
use Jeka\Money\RateProvider\RateProvider;

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
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @var RateProvider
     */
    private $provider;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Dispatcher $dispatcher, RateProvider $provider)
    {
        parent::__construct();
        $this->dispatcher = $dispatcher;
        $this->provider = $provider;
    }

    /**
     * Execute the console command
     *
     * @return mixed|void
     */
    public function handle()
    {
        $rates = $this->provider->getRates()->mapByCodes();

        foreach ($this->getCurrenciesByCodes(array_keys($rates)) as $currency) {
            $this->updateRate($currency, $rates[$currency->code]);
        }

        $this->info("Currency rates have been updated!");
    }

    /**
     * Get currencies by the given codes.
     *
     * @param array $codes
     * @return Collection
     */
    private function getCurrenciesByCodes(array $codes): Collection
    {
        return Currency::whereIn('code', $codes)->get();
    }

    /**
     * Update the rate for the given currency.
     *
     * @param Currency $currency
     * @param Rate $rate
     */
    private function updateRate(Currency $currency, Rate $rate): void
    {
        $currency->updateRate($rate->getValue());

        $this->dispatcher->dispatch(new CurrencyRateUpdated($currency));

        $this->line("Rate has been updated for the currency {$rate->getCode()} with a value {$rate->getValue()}");
    }
}
