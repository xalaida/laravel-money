<?php

namespace Nevadskiy\Money\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Nevadskiy\Money\Casts\AsRate;
use Nevadskiy\Money\Events;
use Nevadskiy\Money\ValueObjects\Rate;

/**
 * @property int id
 * @property string code
 * @property string name
 * @property string symbol
 * @property int precision
 * @property Rate rate
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Currency extends Model
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rate' => AsRate::class,
    ];

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
     * Get the code of the currency.
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Get the major unit multiplier according to the precision.
     */
    public function getMajorMultiplier(): int
    {
        return 10 ** $this->precision;
    }
}
