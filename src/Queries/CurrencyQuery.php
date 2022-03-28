<?php

namespace Nevadskiy\Money\Queries;

use Illuminate\Database\Eloquent\Collection;
use Nevadskiy\Money\Models\Currency;

interface CurrencyQuery
{
    /**
     * Get all available currencies.
     */
    public function all(): Collection;

    /**
     * Get a currency by the given ID.
     */
    public function getById($id): Currency;

    /**
     * Get a currency by the given code.
     */
    public function getByCode(string $code): Currency;

    /**
     * Get the default application currency.
     */
    public function default(): Currency;
}
