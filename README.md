# Laravel Money

## Installation

You can install the package via composer:
```
composer require jeka/laravel-money
```

Add cast to a model.
```
/**
 * The attributes that should be cast.
 *
 * @var array
 */
protected $casts = [
    'price' => \Jeka\Money\Money::class,
];
``` 

Add money attributes to according table.
```
Schema::create('products', static function (Blueprint $table) {
    $table->bigInteger('price_amount')->unsigned();
    $table->foreignUuid('price_currency_id')->constrained('currencies')->onDelete('cascade');
});
```

Now the price field will be casted into Money value object.


## TODO
- [ ] add possibility to disable locale tracking for formatter 
- [ ] add possibility to specify concrete formatter locale 
- [ ] add possibility to specify concrete formatter format
- [ ] add possibility to render money without decimals 
- [ ] add possibility to render money in custom formats (example: '%SU% %code%', '%code% %SU%') 
