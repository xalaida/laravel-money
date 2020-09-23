<?php

declare(strict_types=1);

namespace Jeka\Money\Queries;

use Illuminate\Cache\Repository as Cache;
use Jeka\Money\Models\Currency;

class CurrencyCacheQueries implements CurrencyQueries
{
    /**
     * @var CurrencyQueries
     */
    private $queries;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * CurrencyCacheQueries constructor.
     */
    public function __construct(CurrencyQueries $queries, Cache $cache)
    {
        $this->queries = $queries;
        $this->cache = $cache;
    }

    /**
     * Get a currency by the given ID.
     */
    public function getById(string $id): Currency
    {
        return $this->cache->tags('currency')->rememberForever("currency:id:{$id}", function () use ($id) {
            return $this->queries->getById($id);
        });
    }

    /**
     * Get a currency by the given code.
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
