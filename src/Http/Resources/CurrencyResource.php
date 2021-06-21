<?php

declare(strict_types=1);

namespace Nevadskiy\Money\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Nevadskiy\Money\Models\Currency;

/**
 * @mixin Currency
 */
class CurrencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'symbol' => $this->symbol,
        ];
    }
}
