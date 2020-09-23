<?php

declare(strict_types=1);

namespace Jeka\Money\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jeka\Money\Events;
use Nevadskiy\Uuid\Uuid;

/**
 * @property string id
 * @property string code
 * @property int precision
 * @property float rate
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Currency extends Model
{
    use HasFactory,
        Uuid;

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
