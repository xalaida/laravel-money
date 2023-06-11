<?php

namespace Nevadskiy\Money\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
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
}
