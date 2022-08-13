# Laravel Money

(Work in progress)

💰 The package provides money and currency features for a Laravel application.

## Installation

You can install the package via composer:

```bash
composer require nevadskiy/laravel-money
```


## Documentation

### Using money cast in the model

Any field can be cast into `Money` instance. To make it castable, add the following code to your model.

```php
/**
 * The attributes that should be cast.
 *
 * @var array
 */
protected $casts = [
    'cost' => \Nevadskiy\Money\Casts\AsMoney::class,
];
``` 


Also, you need to add the following fields to the model's database table.

```php
Schema::create('products', function (Blueprint $table) {
    $table->bigInteger('cost_amount')->unsigned();
    $table->foreignUuid('cost_currency_id')->constrained('currencies');
});
```


## Seed currencies 

```bash
php artisan currencies:seed
```


## TODO

- [ ] update doc
- [ ] add changelog and other meta files
- [ ] cover with tests
- [ ] refactor exceptions to put message inside
- [ ] add possibility to seed custom currency (provide callback)
- [ ] add different resolvers to `SetCurrencyMiddleware` (CookieResolver, GeoIPResolver, QueryResolver, UserResolver, etc)
- [ ] add Money::parse() method to receive data from front-end
- [ ] add possibility to disable locale tracking for formatter
- [ ] add possibility to specify concrete formatter format
- [ ] add possibility to render money without decimals
- [ ] introduce the CurrencyInterface that allow to not extend default currency using custom currency
- [ ] allow using plain object currency (not model) as the currency instance for the money (probably possible using interface)
- [ ] add possibility to use currency code instead of ID (in the cast)
- [ ] add possibility to use package with only single (default anonymous) currency
- [ ] add possibility to render money in custom formats (example: '%SU% %code%', '%code% %SU%')
- [ ] add install instruction about cron registration for rates
- [ ] add config parameter as locale (default formatter locale) (add support for 'app' value as locale)
- [ ] add possibility to extend migration
- [ ] add command to show outdated rates
- [ ] integrations with laravel cashier
