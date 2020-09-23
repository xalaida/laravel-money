<?php

declare(strict_types=1);

namespace Jeka\Money;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Events\LocaleUpdated;
use Illuminate\Support\ServiceProvider;
use Jeka\Money\Converter\Converter;
use Jeka\Money\Converter\DefaultConverter;
use Jeka\Money\Formatter\Formatter;
use Jeka\Money\Formatter\IntlFormatter;
use Jeka\Money\Listeners\InvalidateCurrencyCache;
use Jeka\Money\Listeners\UpdateFormatterLocale;
use Jeka\Money\Models\Currency;
use Jeka\Money\Queries\CurrencyCacheQueries;
use Jeka\Money\Queries\CurrencyEloquentQueries;
use Jeka\Money\Queries\CurrencyQueries;

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
            UpdateFormatterLocale::class,
        ],

        Events\CurrencyCreated::class => [
            InvalidateCurrencyCache::class,
        ],

        Events\CurrencyUpdated::class => [
            InvalidateCurrencyCache::class,
        ],

        Events\CurrencyDeleted::class => [
            InvalidateCurrencyCache::class,
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
     * Register any package formatter.
     */
    private function registerFormatter(): void
    {
        $this->app->singleton(Formatter::class, function () {
            return new IntlFormatter($this->app->getLocale());
        });
    }

    /**
     * Register any package converter.
     */
    private function registerConverter(): void
    {
        $this->app->singleton(Converter::class, static function () {
            return new DefaultConverter();
        });
    }

    /**
     * Register any package currency queries.
     */
    private function registerCurrencyQueries(): void
    {
        $this->app->singleton(CurrencyQueries::class, CurrencyEloquentQueries::class);

        $this->app->extend(CurrencyQueries::class, function (CurrencyQueries $queries) {
            return $this->app->make(CurrencyCacheQueries::class, [
                'queries' => $queries
            ]);
        });
    }

    /**
     * Register any package configurations.
     */
    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/money.php', self::NAME);
    }

    /**
     * Boot any package console commands.
     */
    private function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                //
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
            'currencies' => Currency::class,
        ]);
    }
}
