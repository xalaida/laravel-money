# Laravel Money (Work in progress)

💰 The package provides money and currency features for a Laravel application.

## Installation

You can install the package via composer:

```bash
composer require nevadskiy/laravel-money
```


## Documentation

### Using money cast in the model

The price field can be casted into Money instance. To make it castable, add the following code to your model.

```php
/**
 * The attributes that should be cast.
 *
 * @var array
 */
protected $casts = [
    'price' => \Nevadskiy\Money\Money::class,
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

```php
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            \Nevadskiy\Money\Database\Seeders\CurrencySeeder::class,
        ]);
    }
}
```
