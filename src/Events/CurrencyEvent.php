<?php

declare(strict_types=1);

namespace Nevadskiy\Money\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Nevadskiy\Money\Models\Currency;

abstract class CurrencyEvent
{
    use Dispatchable, SerializesModels;

    /**
     * @var Currency
     */
    public $currency;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Currency $currency)
    {
        $this->currency = $currency;
    }
}
