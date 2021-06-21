<?php

declare(strict_types=1);

namespace Nevadskiy\Money\Queries;

use Illuminate\Cache\Repository as Cache;
use Illuminate\Database\Eloquent\Collection;
use Nevadskiy\Money\Models\Currency;

class CurrencyCacheQueries implements CurrencyQueries
{
    /**
     * The base queries instance.
     *
     * @var CurrencyQueries
     */
    private $queries;

    /**
     * The cache instance.
     *
     * @var Cache
     */
    private $cache;

    /**
     * Make a new queries instance.
     */
    public function __construct(CurrencyQueries $queries, Cache $cache)
    {
        $this->queries = $queries;
        $this->cache = $cache;
    }

    /**
     * @inheritDoc
     */
    public function all(): Collection
    {
        return $this->cache->tags('currency')->rememberForever('currency:all', function () {
            return $this->queries->all();
        });
    }

    /**
     * @inheritDoc
     */
    public function getById(string $id): Currency
    {
        return $this->cache->tags('currency')->rememberForever("currency:id:{$id}", function () use ($id) {
            return $this->queries->getById($id);
        });
    }

    /**
     * @inheritDoc
     */
    public function getByCode(string $code): Currency
    {
        return $this->cache->tags('currency')->rememberForever("currency:code:{$code}", function () use ($code) {
            return $this->queries->getByCode($code);
        });
    }

    /**
     * Invalidate the currency cache.
     */
    public function invalidate(): void
    {
        $this->cache->tags('currency')->flush();
    }
}
