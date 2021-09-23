<?php

namespace Nevadskiy\Money\Queries;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Nevadskiy\Money\Models\Currency;

class CurrencyEloquentQueries implements CurrencyQueries
{
    /**
     * The default currency code.
     *
     * @var string
     */
    protected $defaultCurrencyCode;

    /**
     * Make a new queries instance.
     */
    public function __construct(string $defaultCurrencyCode)
    {
        $this->defaultCurrencyCode = $defaultCurrencyCode;
    }

    /**
     * @inheritDoc
     */
    public function all(): Collection
    {
        return Currency::query()->get();
    }

    /**
     * @inheritDoc
     */
    public function getById(string $id): Currency
    {
        return Currency::query()->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function getByCode(string $code): Currency
    {
        return Currency::query()->where('code', Str::upper($code))->firstOrFail();
    }

    /**
     * @inheritDoc
     */
    public function default(): Currency
    {
        return $this->getByCode($this->defaultCurrencyCode);
    }
}
