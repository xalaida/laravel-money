<?php

declare(strict_types=1);

namespace Jeka\Money\Http\Controllers\Api;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Jeka\Money\Http\Resources\CurrencyResource;
use Jeka\Money\Queries\CurrencyQueries;

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
