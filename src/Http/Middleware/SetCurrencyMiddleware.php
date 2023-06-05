<?php

declare(strict_types=1);

namespace Nevadskiy\Money\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Nevadskiy\Money\Models\Currency;
use Nevadskiy\Money\Queries\CurrencyQuery;

final class SetCurrencyMiddleware
{
    /**
     * The currency query instance.
     *
     * @var CurrencyQuery
     */
    private $currencies;

    /**
     * Make a new middleware instance.
     */
    public function __construct(CurrencyQuery $currencies)
    {
        $this->currencies = $currencies;
    }

    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->attributes->set('currency', $this->getCurrencyFromRequest($request));

        return $next($request);
    }

    /**
     * Get a currency instance from the request.
     */
    private function getCurrencyFromRequest(Request $request): Currency
    {
        $currencyCode = $this->parseCurrency($request);

        if (! $currencyCode) {
            return $this->currencies->default();
        }

        try {
            return $this->currencies->getByCode($currencyCode);
        } catch (ModelNotFoundException $e) {
            return $this->currencies->default();
        }
    }

    /**
     * Parse valid currency code from the request.
     */
    private function parseCurrency(Request $request): ?string
    {
        $code = $request->query('currency');

        if (! $code) {
            return null;
        }

        if (! is_string($code)) {
            return null;
        }

        if (Str::length($code) !== 3) {
            return null;
        }

        return $code;
    }
}
