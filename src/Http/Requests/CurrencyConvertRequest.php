<?php

declare(strict_types=1);

namespace Nevadskiy\Money\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CurrencyConvertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'from' => ['sometimes', 'required', 'string'],
            'to' => ['sometimes', 'required', 'string'],
            'amount' => ['required', 'numeric'],
        ];
    }
}
