<?php

namespace Nevadskiy\Money\Http\Controllers\Api;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Nevadskiy\Money\Http\Resources\CurrencyResource;
use Nevadskiy\Money\Queries\CurrencyQuery;

final class CurrencyController
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
    public function index(): AnonymousResourceCollection
    {
        return CurrencyResource::collection(
            $this->currencies->all()
        );
    }
}
