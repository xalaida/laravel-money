<?php

namespace Nevadskiy\Money\Converter;

use function call_user_func;

class DefaultConverterFactory
{
    /**
     * The default currency resolver function.
     *
     * @var null|callable
     */
    public static $resolver;

    /**
     * Add a default currency resolver for the converter.
     */
    public static function resolveDefaultCurrencyUsing(callable $resolver): void
    {
        self::$resolver = $resolver;
    }

    /**
     * Create the currency converter instance.
     */
    public static function create(): DefaultConverter
    {
        if (self::$resolver) {
            return new DefaultConverter(call_user_func(self::$resolver));
        }

        return new DefaultConverter();
    }
}
