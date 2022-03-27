<?php

namespace Nevadskiy\Money\Models;

use Illuminate\Database\Eloquent\Model;
use RuntimeException;

class CurrencyResolver
{
    /**
     * The default model name of the currency.
     */
    protected const DEFAULT = Currency::class;

    /**
     * The class name of the currency model.
     *
     * @var string
     */
    protected static $modelName = self::DEFAULT;

    /**
     * Specify the class name of the currency model.
     */
    public static function use(string $modelName): void
    {
        static::assertClassExists($modelName);

        static::$modelName = $modelName;
    }

    /**
     * Specify the default currency name as the currency name.
     */
    public static function useDefault(): void
    {
        static::use(self::DEFAULT);
    }

    /**
     * Resolve the currency model instance.
     */
    public static function resolve(): Model
    {
        $class = static::modelName();

        return new $class();
    }

    /**
     * Get the model name of the currency.
     */
    public static function modelName(): string
    {
        return static::$modelName;
    }

    /**
     * Assert that the given class exists.
     */
    protected static function assertClassExists(string $modelName): void
    {
        if (! class_exists($modelName)) {
            throw new RuntimeException("Class {$modelName} does not exist.");
        }
    }
}
