<?php

namespace Nevadskiy\Money\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Nevadskiy\Money\Exceptions\CurrencyMismatchException;
use Nevadskiy\Money\Models\Currency;
use Nevadskiy\Money\Queries\CurrencyQuery;
use Nevadskiy\Money\Money;

class AsMoneyOf implements CastsAttributes
{
    /**
     * The currency code of the money.
     *
     * @var string
     */
    protected $currencyCode;

    /**
     * Make a new cast instance.
     */
    public function __construct(string $currencyCode = null)
    {
        $this->currencyCode = $currencyCode ?: $this->currencies()->default()->getCode();
    }

    /**
     * @inheritDoc
     */
    public function get($model, string $key, $value, array $attributes): ?Money
    {
        if (null === $value) {
            return null;
        }

        return new Money($value, $this->getCurrency());
    }

    /**
     * @inheritDoc
     */
    public function set($model, string $key, $value, array $attributes): array
    {
        if (null === $value) {
            return [];
        }

        $this->assertValueIsMoneyInstance($value);
        $this->assertMoneyCurrencyMatches($value);

        return [
            $key => $value->getMinorUnits(),
        ];
    }

    /**
     * Get the currency code.
     */
    public function getCurrencyCode(): string
    {
        return Str::upper($this->currencyCode);
    }

    /**
     * Get the currency instance.
     */
    public function getCurrency(): Currency
    {
        return $this->currencies()->getByCode($this->getCurrencyCode());
    }

    /**
     * Get the currencies query instance.
     */
    private function currencies(): CurrencyQuery
    {
        return resolve(CurrencyQuery::class);
    }

    /**
     * Assert that the given value is a money instance.
     */
    protected function assertValueIsMoneyInstance($value): void
    {
        if (! $value instanceof Money) {
            throw new InvalidArgumentException('The given value is not a Money instance.');
        }
    }

    /**
     * Assert that the given currency matches the current currency.
     */
    private function assertMoneyCurrencyMatches(Money $money): void
    {
        if ($money->getCurrency()->code !== $this->getCurrencyCode()) {
            throw new CurrencyMismatchException();
        }
    }
}
