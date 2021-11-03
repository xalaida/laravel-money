<?php

namespace Nevadskiy\Money\Queries;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Nevadskiy\Money\Exceptions\DefaultCurrencyMissingException;
use Nevadskiy\Money\Models\Currency;

class CurrencyEloquentQuery implements CurrencyQuery
{
    /**
     * The default currency code.
     *
     * @var null|string
     */
    protected $defaultCurrencyCode;

    /**
     * Make a new queries instance.
     */
    public function __construct(string $defaultCurrencyCode = null)
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
        return Currency::query()->where('code', $this->normalizeCode($code))->firstOrFail();
    }

    /**
     * @inheritDoc
     */
    public function default(): Currency
    {
        if (! $this->defaultCurrencyCode) {
            throw new DefaultCurrencyMissingException();
        }

        return $this->getByCode($this->defaultCurrencyCode);
    }

    /**
     * Get the normalized currency code.
     */
    protected function normalizeCode(string $code): string
    {
        return Str::upper($code);
    }
}
