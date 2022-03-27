<?php

namespace Nevadskiy\Money\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Nevadskiy\Money\Exceptions\DefaultCurrencyMissingException;
use Nevadskiy\Money\Models\Currency;
use Nevadskiy\Money\Models\CurrencyResolver;

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
        return $this->query()->get();
    }

    /**
     * @inheritDoc
     */
    public function getById($id): Currency
    {
        return $this->query()->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function getByCode(string $code): Currency
    {
        return $this->query()->where('code', $this->normalizeCode($code))->firstOrFail();
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

    /**
     * Get the currency query builder instance.
     */
    protected function query(): Builder
    {
        return CurrencyResolver::resolve()->newQuery();
    }
}
