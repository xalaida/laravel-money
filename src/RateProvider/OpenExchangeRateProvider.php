<?php

namespace Nevadskiy\Money\RateProvider;

use Illuminate\Http\Client\Factory as Http;
use Illuminate\Http\Client\RequestException;

class OpenExchangeRateProvider implements RateProvider
{
    /**
     * The HTTP client instance.
     *
     * @var Http
     */
    protected $http;

    /**
     * The application ID.
     *
     * @var string
     */
    protected $appId;

    /**
     * Make a new provider instance.
     */
    public function __construct(Http $http, string $appId)
    {
        $this->http = $http;
        $this->appId = $appId;
    }

    /**
     * @inheritDoc
     */
    public function getRates(): array
    {
        return $this->fetch()['rates'];
    }

    /**
     * Fetch the currency rates.
     *
     * @throws RequestException
     */
    protected function fetch(): array
    {
        return $this->http->get($this->url())->throw()->json();
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
