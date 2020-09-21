<?php

declare(strict_types=1);

namespace Jeka\Money;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Events\LocaleUpdated;
use Illuminate\Support\ServiceProvider;
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
     * The module's name.
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
     * Register any module services.
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->registerFormatter();
        $this->registerCurrencyQueries();
    }

    /**
     * Bootstrap any module services.
     */
    public function boot(): void
    {
        $this->bootCommands();
        $this->bootEvents();
        $this->bootMigrations();
        $this->bootMorphMap();
    }

    /**
     * Register any module formatter.
     */
    private function registerFormatter(): void
    {
        $this->app->singleton(Formatter::class, function () {
            return new IntlFormatter($this->app->getLocale());
        });
    }

    /**
     * Register any module currency queries.
     */
    private function registerCurrencyQueries(): void
    {
        $this->app->singleton(CurrencyQueries::class, CurrencyEloquentQueries::class);

        $this->app->extend(CurrencyQueries::class, static function (CurrencyQueries $queries) {
            return new CurrencyCacheQueries($queries);
        });
    }

    /**
     * Register any module configurations.
     */
    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/money.php', self::NAME);
    }

    /**
     * Boot any module console commands.
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
     * Boot any module events.
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
     * Boot any module migrations.
     */
    private function bootMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Boot module morph map.
     */
    private function bootMorphMap(): void
    {
        Relation::morphMap([
            'currencies' => Currency::class,
        ]);
    }
}
