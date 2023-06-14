<?php

namespace Nevadskiy\Money\RateProvider;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Http\Client\Factory as Http;

/**
 * @todo refactor by using separate http client psr interface + psr cache decorator on top of that.
 */
class OpenExchangeRateProvider extends BaseCurrencyRateProvider
{
    /**
     * The HTTP client instance.
     *
     * @var Http
     */
    protected $http;

    /**
     * The cache instance.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * The application ID.
     *
     * @var string
     */
    protected $appId;

    /**
     * The latest exchange rates response.
     *
     * @var array
     */
    protected $response;

    /**
     * Make a new provider instance.
     */
    public function __construct(Http $http, Cache $cache, string $appId)
    {
        $this->http = $http;
        $this->cache = $cache;
        $this->appId = $appId;
    }

    /**
     * @inheritDoc
     */
    protected function getRates(): array
    {
        return $this->getResponse()['rates'];
    }

    /**
     * @inheritdoc
     */
    protected function getBaseCurrency(): string
    {
        return $this->getResponse()['base'];
    }

    /**
     * Fetch currency exchange rates.
     */
    protected function getResponse(): array
    {
        if (! $this->response) {
            $this->response = $this->fetch();
        }

        return $this->response;
    }

    /**
     * Fetch currency exchange rates.
     */
    protected function fetch()
    {
        return $this->cache->remember(self::class, now()->addDay(), function () {
            return $this->http->get($this->url())->throw()->json();
        });
    }

    /**
     * Get the final URL.
     */
    protected function url(): string
    {
        return $this->baseUrl() . '?' . http_build_query(['app_id' => $this->appId]);
    }

    /**
     * Get the base URL.
     */
    protected function baseUrl(): string
    {
        return 'https://openexchangerates.org/api/latest.json';
    }
}
