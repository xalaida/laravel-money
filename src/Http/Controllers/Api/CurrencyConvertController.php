<?php

namespace Nevadskiy\Money\Http\Controllers\Api;

use Illuminate\Http\Request;
use Nevadskiy\Money\Http\Requests\CurrencyConvertRequest;
use Nevadskiy\Money\Http\Resources\MoneyResource;
use Nevadskiy\Money\Models\Currency;
use Nevadskiy\Money\Queries\CurrencyQuery;
use Nevadskiy\Money\ValueObjects\Money;

final class CurrencyConvertController
{
    /**
     * The currency query instance.
     *
     * @var CurrencyQuery
     */
    private $currencies;

    /**
     * Make a new controller instance.
     */
    public function __construct(CurrencyQuery $currencies)
    {
        $this->currencies = $currencies;
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
    private function getSourceCurrency(Request $request): Currency
    {
        return $request->query('from')
            ? $this->currencies->getByCode($request->query('from'))
            : $this->currencies->default();
    }

    /**
     * Get the target currency from the request.
     * Use request currency if 'to' is not set.
     */
    private function getTargetCurrency(Request $request): Currency
    {
        return $request->query('to')
            ? $this->currencies->getByCode($request->query('to'))
            : $request->attributes->get('currency');
    }
}
