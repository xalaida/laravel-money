<?php

namespace Nevadskiy\Money\Http\Controllers\Api;

use Illuminate\Http\Request;
use Nevadskiy\Money\Http\Resources\MoneyResource;
use Nevadskiy\Money\Queries\CurrencyQueries;
use Nevadskiy\Money\ValueObjects\Money;

final class CurrencyConvertController
{
    /**
     * The queries instance.
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
    public function __invoke(Request $request): MoneyResource
    {
        // Validate
        $request->validate([
            'from' => ['required', 'string', 'uuid'],
            'to' => ['sometimes', 'required', 'string', 'uuid'],
            'amount' => ['required', 'numeric'],
        ]);

        // Init
        $money = Money::fromMinorUnits(
            $request->get('amount'),
            $this->queries->getById($request->get('from'))
        );

        // Convert
        $money = $money->convert(
            $request->get('to')
                ? $this->queries->getById($request->get('to'))
                : null
        );

        // Format
        return MoneyResource::make($money);
    }
}
