<?php

namespace Nevadskiy\Money\Serializers;

use Nevadskiy\Money\Money;

class ArraySerializer implements Serializer
{
    /**
     * @inheritdoc
     */
    public function serialize(Money $money): array
    {
        return [
            'amount' => $money->getAmount(),
            'currency' => $money->getCurrency(),
        ];
    }
}
