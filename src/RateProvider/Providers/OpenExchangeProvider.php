<?php

namespace Nevadskiy\Money\RateProvider\Providers;

use Illuminate\Http\Client\Factory as Http;
use Illuminate\Http\Client\RequestException;
use Nevadskiy\Money\RateProvider\RateProvider;
use Nevadskiy\Money\ValueObjects\Rate;

class OpenExchangeProvider implements RateProvider
{
    /**
     * The HTTP client instance.
     *
     * @var Http
     */
    protected $http;

    /**
     * The provider application ID.
     *
     * @var string
     */
    protected $appId;

    /**
     * OpenExchangeProvider constructor.
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
        $data = [];

        foreach ($this->fetchRates() as $code => $rate) {
            $data[$code] = new Rate($rate);
        }

        return $data;
    }

    /**
     * Fetch the currency rates.
     *
     * @throws RequestException
     */
    protected function fetchRates(): array
    {
        $response = $this->http->get($this->url());

        return $response->throw()->json('rates');
    }

    /**
     * Get the final URL.
     */
    protected function url(): string
    {
        return vsprintf('%s?%s', [
            $this->baseUrl(), http_build_query(['app_id' => $this->appId]),
        ]);
    }

    /**
     * Get the base URL.
     */
    protected function baseUrl(): string
    {
        return 'https://openexchangerates.org/api/latest.json';
    }
}
