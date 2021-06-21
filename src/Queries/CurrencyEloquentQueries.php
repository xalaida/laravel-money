<?php

declare(strict_types=1);

namespace Nevadskiy\Money\Queries;

use Illuminate\Database\Eloquent\Collection;
use Nevadskiy\Money\Models\Currency;

class CurrencyEloquentQueries implements CurrencyQueries
{
    /**
     * @inheritDoc
     */
    public function all(): Collection
    {
        return Currency::query()
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function getById(string $id): Currency
    {
        return Currency::query()
            ->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function getByCode(string $code): Currency
    {
        return Currency::query()
            ->where('code', $code)
            ->firstOrFail();
    }
}
