<?php

namespace Nevadskiy\Money\Serializers;

use Nevadskiy\Money\Money;

class StringSerializer implements Serializer
{
    /**
     * @inheritdoc
     */
    public function serialize(Money $money): string
    {
        return (string) $money;
    }
}
