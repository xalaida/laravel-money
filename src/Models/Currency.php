<?php

namespace Nevadskiy\Money\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Nevadskiy\Money\Events;
use Nevadskiy\Money\Exceptions\InvalidRateException;
use Nevadskiy\Uuid\Uuid;

/**
 * @property string id
 * @property string code
 * @property string name
 * @property string symbol
 * @property int precision
 * @property float rate
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Currency extends Model
{
    use Uuid;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => Events\CurrencyCreated::class,
        'updated' => Events\CurrencyUpdated::class,
        'deleted' => Events\CurrencyDeleted::class,
    ];

    /**
     * Set the currency's code attribute.
     */
    public function setCodeAttribute(string $code): void
    {
        $this->attributes['code'] = Str::upper($code);
    }

    /**
     * Update rate of the currency.
     */
    public function updateRate(float $rate): void
    {
        $this->assertPositiveRate($rate);

        $this->rate = $rate;
        $this->save();
    }

    /**
     * Assert that the given rate is more than zero.
     */
    private function assertPositiveRate(float $rate): void
    {
        if ($rate <= 0) {
            throw new InvalidRateException();
        }
    }
}
