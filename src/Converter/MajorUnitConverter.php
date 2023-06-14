<?php

namespace Nevadskiy\Money\Converter;

use Nevadskiy\Money\Money;
use Nevadskiy\Money\RateProvider\RateProvider;

class MajorUnitConverter implements Converter
{
    /**
     * The rate provider instance.
     *
     * @var RateProvider
     */
    protected $rateProvider;

    /**
     * Make a new converter instance.
     */
    public function __construct(RateProvider $rateProvider)
    {
        $this->rateProvider = $rateProvider;
    }

    /**
     * @inheritDoc
     */
    public function convert(Money $money, string $currency): Money
    {
        return Money::fromMajorUnits(
            $money->getMajorUnits() * $this->rateProvider->getRate($money->getCurrency(), $currency), $currency
        );
    }
}
