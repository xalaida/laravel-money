<?php

declare(strict_types=1);

namespace Jeka\Money\Queries;

use Jeka\Money\Models\Currency;

class CurrencyEloquentQueries implements CurrencyQueries
{
    /**
     * Get a currency by the given ID.
     */
    public function getById(string $id): Currency
    {
        return Currency::findOrFail($id);
    }

    /**
     * Get a currency by the given code.
     */
    public function getByCode(string $code): Currency
    {
        return Currency::where('code', $code)->firstOrFail();
    }
}
