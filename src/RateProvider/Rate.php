<?php

declare(strict_types=1);

namespace Jeka\Money\RateProvider;

class Rate
{
    /**
     * Code of the currency.
     *
     * @var string
     */
    private $code;

    /**
     * Value of the rate.
     *
     * @var float
     */
    private $value;

    /**
     * Rate constructor.
     */
    public function __construct(string $code, float $value)
    {
        $this->code = $code;
        $this->value = $value;
    }

    /**
     * Get code of the currency.
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Get value of the rate.
     */
    public function getValue(): float
    {
        return $this->value;
    }
}
