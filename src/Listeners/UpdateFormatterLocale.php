<?php

namespace Nevadskiy\Money\Listeners;

use Illuminate\Foundation\Events\LocaleUpdated;
use Nevadskiy\Money\Formatter\Formatter;

class UpdateFormatterLocale
{
    /**
     * The money formatter.
     *
     * @var Formatter
     */
    protected $formatter;

    /**
     * Create the event listener.
     */
    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Handle the event.
     */
    public function handle(LocaleUpdated $event): void
    {
        $this->formatter->setDefaultLocale($event->locale);
    }
}
