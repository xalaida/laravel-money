<?php

namespace Nevadskiy\Money\Listeners;

use Nevadskiy\Money\Converter\Converter;
use Nevadskiy\Money\Events\DefaultCurrencyUpdated;

class UpdateDefaultConverterCurrency
{
    /**
     * The money converter.
     *
     * @var Converter
     */
    protected $converter;

    /**
     * Create the event listener.
     */
    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * Handle the event.
     */
    public function handle(DefaultCurrencyUpdated $event): void
    {
        $this->converter->setDefaultCurrency($event->currency);
    }
}
