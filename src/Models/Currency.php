<?php

namespace Nevadskiy\Money\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Nevadskiy\Money\Events;

/**
 * @property int id
 * @property string code
 * @property string name
 * @property string symbol
 * @property int scale
 * @property float rate
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Currency extends Model
{
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
     * Get the major unit multiplier according to the scale.
     */
    public function getMajorMultiplier(): int
    {
        return 10 ** $this->scale;
    }
}
