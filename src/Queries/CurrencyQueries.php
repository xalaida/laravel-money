<?php

declare(strict_types=1);

namespace Jeka\Money\Queries;

use Jeka\Money\Models\Currency;

interface CurrencyQueries
{
    /**
     * Get a currency by the given ID.
     */
    public function getById(string $id): Currency;

    /**
     * Get a currency by the given code.
     */
    public function getByCode(string $code): Currency;
}
