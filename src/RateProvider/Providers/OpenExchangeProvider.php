<?php

namespace Nevadskiy\Money\RateProvider\Providers;

use Illuminate\Http\Client\Factory as Http;
use Illuminate\Http\Client\RequestException;
use Nevadskiy\Money\RateProvider\RateProvider;
use Nevadskiy\Money\ValueObjects\Rate;

class OpenExchangeProvider implements RateProvider
{
    /**
     * @var Http
     */
    private $http;

    /**
     * @var string
     */
    private $appId;

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
    private function fetchRates(): array
    {
        $response = $this->http->get($this->url());

        return $response->throw()->json('rates');
    }

    /**
     * Get the final URL.
     */
    private function url(): string
    {
        return vsprintf('%s?%s', [
            $this->baseUrl(), http_build_query(['app_id' => $this->appId]),
        ]);
    }

    /**
     * Get the base URL.
     */
    private function baseUrl(): string
    {
        return 'https://openexchangerates.org/api/latest.json';
    }
}
