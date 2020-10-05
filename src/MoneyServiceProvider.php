<?php

declare(strict_types=1);

namespace Jeka\Money;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Events\LocaleUpdated;
use Illuminate\Support\ServiceProvider;

class MoneyServiceProvider extends ServiceProvider
{
    /**
     * The package's name.
     */
    private const NAME = 'money';

    /**
     * The event listener mappings for the package.
     *
     * @var array
     */
    protected $listen = [
        LocaleUpdated::class => [
            Listeners\UpdateFormatterLocale::class,
        ],

        Events\CurrencyCreated::class => [
            Listeners\InvalidateCurrencyCache::class,
        ],

        Events\CurrencyUpdated::class => [
            Listeners\InvalidateCurrencyCache::class,
        ],

        Events\CurrencyDeleted::class => [
            Listeners\InvalidateCurrencyCache::class,
        ],
    ];

    /**
     * Register any package services.
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->registerFormatter();
        $this->registerConverter();
        $this->registerRateProviders();
        $this->registerCurrencyQueries();
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->bootCommands();
        $this->bootEvents();
        $this->bootMigrations();
        $this->bootMorphMap();
    }

    /**
     * Register any package configurations.
     */
    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/money.php', self::NAME);
    }

    /**
     * Register any package formatter.
     */
    private function registerFormatter(): void
    {
        $this->app->singleton(Formatter\Formatter::class, function () {
            return new Formatter\IntlFormatter($this->app->getLocale());
        });
    }

    /**
     * Register any package converter.
     */
    private function registerConverter(): void
    {
        $this->app->singleton(Converter\Converter::class, static function () {
            return new Converter\DefaultConverter();
        });
    }

    /**
     * Register any package rate providers.
     */
    private function registerRateProviders(): void
    {
        $this->registerOpenExchangeProvider();

        $this->app->singleton(RateProvider\RateProvider::class, function () {
            return $this->app[$this->app['config']['money']['default_rate_provider']];
        });
    }

    /**
     * Register any package currency queries.
     */
    private function registerCurrencyQueries(): void
    {
        $this->app->singleton(Queries\CurrencyQueries::class, Queries\CurrencyEloquentQueries::class);

        $this->app->extend(Queries\CurrencyQueries::class, function (Queries\CurrencyQueries $queries) {
            return $this->app->make(Queries\CurrencyCacheQueries::class, [
                'queries' => $queries,
            ]);
        });
    }

    /**
     * Boot any package console commands.
     */
    private function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\UpdateRatesCommand::class,
                Console\SeedCurrenciesCommand::class,
            ]);
        }
    }

    /**
     * Boot any package events.
     */
    private function bootEvents(): void
    {
        $dispatcher = $this->app[Dispatcher::class];

        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                $dispatcher->listen($event, $listener);
            }
        }
    }

    /**
     * Boot any package migrations.
     */
    private function bootMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Boot package morph map.
     */
    private function bootMorphMap(): void
    {
        Relation::morphMap([
            'currencies' => Models\Currency::class,
        ]);
    }

    /**
     * Register the open exchange rate provider.
     */
    private function registerOpenExchangeProvider(): void
    {
        $this->app->bind('open_exchange_rates', RateProvider\Providers\OpenExchangeProvider::class);

        $this->app->when(RateProvider\Providers\OpenExchangeProvider::class)
            ->needs('$appId')
            ->give($this->app['config']['money']['rate_providers']['open_exchange_rates']['app_id']);
    }
}
