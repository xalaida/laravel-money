<?php

declare(strict_types=1);

namespace Jeka\Money\Listeners;

use Illuminate\Foundation\Events\LocaleUpdated;
use Jeka\Money\Formatter\Formatter;

class UpdateFormatterLocale
{
    /**
     * The money formatter.
     *
     * @var Formatter
     */
    private $formatter;

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
        $this->formatter->setLocale($event->locale);
    }
}
