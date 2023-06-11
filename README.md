# Laravel Money

💰 The package provides money and currency features for a Laravel application.

## Installation

You can install the package via composer:

```bash
composer require nevadskiy/laravel-money
```

## Documentation

### Using money cast in the model

```php
use Nevadskiy\Money\Casts\AsMoney;

/**
 * The attributes that should be cast.
 *
 * @var array
 */
protected $casts = [
    'cost' => AsMoney::class,
];
```

```php
Schema::create('products', function (Blueprint $table) {
    $table->bigInteger('cost')->unsigned();
});
```
