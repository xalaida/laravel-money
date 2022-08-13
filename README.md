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

