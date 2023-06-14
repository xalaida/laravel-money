<?php

namespace Nevadskiy\Money\Serializers;

use Nevadskiy\Money\Money;

interface Serializer
{
    /**
     * Serialize the money instance.
     *
     * @return mixed
     */
    public function serialize(Money $money);
}
