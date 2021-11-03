<?php

declare(strict_types=1);

namespace Nevadskiy\Money\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Nevadskiy\Money\Models\Currency;
use Nevadskiy\Money\Queries\CurrencyQueries;

final class SetCurrencyMiddleware
{
    /**
     * The currency queries instance.
     *
     * @var CurrencyQueries
     */
    private $currencyQueries;

    /**
     * Make a new middleware instance.
     */
    public function __construct(CurrencyQueries $currencyQueries)
    {
        $this->currencyQueries = $currencyQueries;
    }

    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->setRequestCurrency($request);

        return $next($request);
    }

    /**
     * TODO: probably add different resolvers (CookieResolver, GeoIPResolver, UserResolver, etc.
     * TODO: cover with tests
     * Set the default converter currency from the request query.
     */
    private function setRequestCurrency(Request $request): void
    {
        // TODO: maybe define strategies here how to update currency (as default, or as request attribute, or custom)
        // TODO: probably it should be saved into request attribute and somewhere else.
        $request->attributes->set('currency', $this->getCurrencyFromRequest($request));
    }

    /**
     * Get currency from the request.
     */
    private function getCurrencyFromRequest(Request $request): Currency
    {
        if (! $request->query('currency')) {
            return $this->currencyQueries->default();
        }

        // TODO: validate currency code here...

        try {
            return $this->currencyQueries->getByCode($request->query('currency'));
        } catch (ModelNotFoundException $e) {
            // TODO: log that currency cannot be resolved by the given code.
            return $this->currencyQueries->default();
        }
    }
}