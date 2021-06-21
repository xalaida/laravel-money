<?php

declare(strict_types=1);

namespace Nevadskiy\Money\Queries;

use Illuminate\Database\Eloquent\Collection;
use Nevadskiy\Money\Models\Currency;

interface CurrencyQueries
{
    /**
     * Get all available currencies.
     */
    public function all(): Collection;

    /**
     * Get a currency by the given ID.
     */
    public function getById(string $id): Currency;

    /**
     * Get a currency by the given code.
     */
    public function getByCode(string $code): Currency;
}
