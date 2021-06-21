<?php

declare(strict_types=1);

namespace Nevadskiy\Money\Tests\Support\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nevadskiy\Money\Money;
use Nevadskiy\Uuid\Uuid;

/**
 * @property string id
 * @property string name
 * @property int price_amount
 * @property string price_currency_id
 * @property Money price
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Product extends Model
{
    use HasFactory;
    use Uuid;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array|bool
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => Money::class,
    ];
}
