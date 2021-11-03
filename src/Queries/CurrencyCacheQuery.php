<?php

namespace Nevadskiy\Money\Queries;

use Illuminate\Cache\Repository as Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Nevadskiy\Money\Models\Currency;

class CurrencyCacheQuery implements CurrencyQuery
{
    /**
     * The base queries instance.
     *
     * @var CurrencyQuery
     */
    protected $currencies;

    /**
     * The cache instance.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * Make a new queries instance.
     */
    public function __construct(CurrencyQuery $currencies, Cache $cache)
    {
        $this->currencies = $currencies;
        $this->cache = $cache;
    }

    /**
     * @inheritDoc
     */
    public function all(): Collection
    {
        return $this->cache->tags('currency')
            ->rememberForever($this->buildCacheKey(['currency', 'all']), function () {
                return $this->currencies->all();
            });
    }

    /**
     * @inheritDoc
     */
    public function getById(string $id): Currency
    {
        return $this->cache->tags('currency')
            ->rememberForever($this->buildCacheKey(['currency', 'id', $id]), function () use ($id) {
                return $this->currencies->getById($id);
            });
    }

    /**
     * @inheritDoc
     */
    public function getByCode(string $code): Currency
    {
        return $this->cache->tags('currency')
            ->rememberForever($this->buildCacheKey(['currency', 'code', $code]), function () use ($code) {
                return $this->currencies->getByCode($code);
            });
    }

    /**
     * @inheritDoc
     */
    public function default(): Currency
    {
        return $this->cache->tags('currency')
            ->rememberForever($this->buildCacheKey(['currency', 'default']), function () {
                return $this->currencies->default();
            });
    }

    /**
     * Invalidate the currency cache.
     */
    public function invalidate(): void
    {
        $this->cache->tags('currency')->flush();
    }

    /**
     * Build the cache key from segments.
     */
    private function buildCacheKey(array $segments): string
    {
        return collect($segments)->map(function (string $segment) {
            return Str::lower($segment);
        })->implode(':');
    }
}
