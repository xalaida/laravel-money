<?php

declare(strict_types=1);

namespace Jeka\Money\Queries;

use Illuminate\Support\Facades\Cache;
use Jeka\Money\Models\Currency;

class CurrencyCacheQueries implements CurrencyQueries
{
    /**
     * @var CurrencyQueries
     */
    private $queries;

    /**
     * CurrencyCacheQueries constructor.
     */
    public function __construct(CurrencyQueries $queries)
    {
        $this->queries = $queries;
    }

    /**
     * Get a currency by the given ID.
     */
    public function getById(string $id): Currency
    {
        // TODO: Replace Cache facade using DI
        return Cache::rememberForever("currency:{$id}", function () use ($id) {
            return $this->queries->getById($id);
        });
    }
}
