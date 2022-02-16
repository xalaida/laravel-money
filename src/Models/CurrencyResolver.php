<?php

namespace Nevadskiy\Money\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyResolver
{
    /**
     * The class name of the currency model.
     *
     * @var string
     */
    private $className;

    /**
     * Make a new currency resolver instance.
     */
    public function __construct(string $className = Currency::class)
    {
        $this->className = $className;
    }

    /**
     * Resolve the model instance.
     */
    public function resolve(): Model
    {
        return new $this->className;
    }
}
