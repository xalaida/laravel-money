<?php

namespace Nevadskiy\Money\Http\Controllers\Api;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Nevadskiy\Money\Http\Resources\CurrencyResource;
use Nevadskiy\Money\Queries\CurrencyQueries;

final class CurrencyController
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
    public function index(): AnonymousResourceCollection
    {
        return CurrencyResource::collection(
            $this->queries->all()
        );
    }
}
