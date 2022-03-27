# Laravel Money (Work in progress)

💰 The package provides money and currency features for a Laravel application.

## Installation

You can install the package via composer:

```bash
composer require nevadskiy/laravel-money
```


## Documentation

### Using money cast in the model

The price field can be cast into Money instance. To make it castable, add the following code to your model.

```php
/**
 * The attributes that should be cast.
 *
 * @var array
 */
protected $casts = [
    'price' => \Nevadskiy\Money\ValueObjects\Money::class,
];
``` 


Also, you need to add the following fields to the model's database table.

```php
Schema::create('products', function (Blueprint $table) {
    $table->bigInteger('price_amount')->unsigned();
    $table->foreignUuid('price_currency_id')->constrained('currencies');
});
```


## Seed currencies 

```bash
php artisan currencies:seed
```


## TODO

- [ ] update doc
- [ ] cover with tests
- [ ] add publish config
- [ ] add possibility to seed custom currency (provide callback)
- [ ] add Money::parse() method to receive data from front-end
- [ ] add possibility to disable locale tracking for formatter
- [ ] add possibility to specify concrete formatter format
- [ ] add possibility to render money without decimals
- [ ] introduce the CurrencyInterface that allow to not extend default currency using custom currency
- [ ] allow using plain object currency (not model) as the currency instance for the money (probably possible using interface)
- [ ] add possibility to use currency code instead of ID (in the cast)
- [ ] add possibility to use package with only single (default anonymous) currency
- [ ] add possibility to render money in custom formats (example: '%SU% %code%', '%code% %SU%')
- [ ] store currency rates history and add config for pruning (i.e. 'keep_history' => '1 year') (can be done using laravel prunable models)
- [ ] add install instruction about cron registration for rates
- [ ] add config parameter as locale (default formatter locale) (add support for 'app' value as locale)
- [ ] add cast with the following syntax: AsMoneyOf::class.'USD'
- [ ] add possibility to use Casts\Money::class directly without needing or resolving container dependencies (resolve them inside)
- [ ] add possibility to extend migration
- [ ] add command to show outdated rates
- [ ] integrations with laravel cashier
