<?php

namespace Nevadskiy\Money\Events;

use Nevadskiy\Money\Models\Currency;
use Nevadskiy\Money\ValueObjects\Rate;

class CurrencyRateUpdated extends CurrencyEvent
{
    /**
     * The rate before update.
     *
     * @var Rate
     */
    public $rateBefore;

    /**
     * The rate after update.
     *
     * @var Rate
     */
    public $rateAfter;

    /**
     * Create a new event instance.
     */
    public function __construct(Currency $currency, Rate $rateBefore, Rate $rateAfter)
    {
        parent::__construct($currency);
        $this->rateBefore = $rateBefore;
        $this->rateAfter = $rateAfter;
    }
}
