# Laravel Money

💰 The package provides money and currency features for a Laravel application.

## Installation

You can install the package via composer:

```bash
composer require nevadskiy/laravel-money
```

## Documentation

### Using money cast in the model

Any field can be cast into `Money` instance. To make it castable, add the following code to your model:

```php
use Nevadskiy\Money\Casts\AsMoney;

/**
 * The attributes that should be cast.
 *
 * @var array
 */
protected $casts = [
    'cost' => AsMoney::class.':UAH',
];
```

### Money cast with dynamic currency

```php
Schema::create('products', function (Blueprint $table) {
    $table->bigInteger('cost')->unsigned();
    $table->string('currency', 3);
});
```

```php
use Nevadskiy\Money\Casts\AsMoney;

/**
 * The attributes that should be cast.
 *
 * @var array
 */
protected $casts = [
    'cost' => AsMoney::class.':[currency]',
];
```

## To Do List

- [ ] use Symfony\Polyfill\Intl\Icu\Currencies for default registry
