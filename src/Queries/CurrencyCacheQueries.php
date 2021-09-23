<?php

namespace Nevadskiy\Money\Queries;

use Illuminate\Cache\Repository as Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Nevadskiy\Money\Models\Currency;

class CurrencyCacheQueries implements CurrencyQueries
{
    /**
     * The base queries instance.
     *
     * @var CurrencyQueries
     */
    protected $queries;

    /**
     * The cache instance.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * The default currency code.
     *
     * @var string
     */
    protected $defaultCurrencyCode;

    /**
     * Make a new queries instance.
     */
    public function __construct(CurrencyQueries $queries, Cache $cache, string $defaultCurrencyCode)
    {
        $this->queries = $queries;
        $this->cache = $cache;
        $this->defaultCurrencyCode = $defaultCurrencyCode;
    }

    /**
     * @inheritDoc
     */
    public function all(): Collection
    {
        return $this->cache->tags('currency')
            ->rememberForever($this->buildCacheKey(['currency', 'all']), function () {
                return $this->queries->all();
            });
    }

    /**
     * @inheritDoc
     */
    public function getById(string $id): Currency
    {
        return $this->cache->tags('currency')
            ->rememberForever($this->buildCacheKey(['currency', 'id', $id]), function () use ($id) {
                return $this->queries->getById($id);
            });
    }

    /**
     * @inheritDoc
     */
    public function getByCode(string $code): Currency
    {
        return $this->cache->tags('currency')
            ->rememberForever($this->buildCacheKey(['currency', 'code', $code]), function () use ($code) {
                return $this->queries->getByCode($code);
            });
    }

    /**
     * @inheritDoc
     */
    public function default(): Currency
    {
        return $this->getByCode($this->defaultCurrencyCode);
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
     *
     * @param array $segments
     * @return string
     */
    private function buildCacheKey(array $segments): string
    {
        return collect($segments)->map(function (string $segment) {
            return Str::lower($segment);
        })->implode(':');
    }
}
