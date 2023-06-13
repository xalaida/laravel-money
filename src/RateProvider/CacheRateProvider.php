<?php

namespace Nevadskiy\Money\RateProvider;

use Illuminate\Contracts\Cache\Repository as Cache;

class CacheRateProvider implements RateProvider
{
    /**
     * The base rate provider instance.
     *
     * @var RateProvider
     */
    protected $rateProvider;

    /**
     * The cache instance.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * Make a new provider instance.
     */
    public function __construct(RateProvider $rateProvider, Cache $cache)
    {
        $this->rateProvider = $rateProvider;
        $this->cache = $cache;
    }

    /**
     * @inheritdoc
     * @todo use PSR cache.
     */
    public function getRates(): array
    {
        return $this->cache->remember(self::class, now()->addDay(), function () {
            return $this->rateProvider->getRates();
        });
    }
}
