<?php

namespace Nevadskiy\Money\Http\Controllers\Api;

use Illuminate\Http\Request;
use Nevadskiy\Money\Http\Requests\CurrencyConvertRequest;
use Nevadskiy\Money\Http\Resources\MoneyResource;
use Nevadskiy\Money\Models\Currency;
use Nevadskiy\Money\Queries\CurrencyQueries;
use Nevadskiy\Money\ValueObjects\Money;

final class CurrencyConvertController
{
    /**
     * The queries instance.
     *
     * @var CurrencyQueries
     */
    private $queries;

    /**
     * Make a new controller instance.
     */
    public function __construct(CurrencyQueries $queries)
    {
        $this->queries = $queries;
    }

    /**
     * The currencies index action.
     */
    public function __invoke(CurrencyConvertRequest $request): MoneyResource
    {
        return MoneyResource::make(
            Money::fromMinorUnits($request->get('amount'), $this->getSourceCurrency($request))
                ->convert($this->getTargetCurrency($request))
        );
    }

    /**
     * Get the source currency from the request.
     * Use the default app currency if the 'from' currency is not defined.
     */
    protected function getSourceCurrency(Request $request): Currency
    {
        return $request->query('from')
            ? $this->queries->getByCode($request->query('from'))
            : $this->queries->default();
    }

    /**
     * Get the target currency from the request.
     * Use request currency if 'to' is not set.
     */
    protected function getTargetCurrency(Request $request): Currency
    {
        return $request->query('to')
            ? $this->queries->getByCode($request->query('to'))
            : $request->attributes->get('currency');
    }
}
