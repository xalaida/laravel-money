<?php

declare(strict_types=1);

namespace Jeka\Money\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Jeka\Money\Money;

/**
 * @mixin Money
 */
class MoneyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     */
    public function toArray($request): array
    {
        return [
            'amount' => $this->getAmount(),
            'formatted' => $this->format(),
            'currency' => CurrencyResource::make($this->getCurrency()),
        ];
    }
}
