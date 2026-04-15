<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CatalogProductIndexRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ids' => ['nullable', 'array'],
            'ids.*' => ['integer', 'min:1'],
            'q' => ['nullable', 'string', 'max:120'],
            'type' => ['nullable', Rule::in([Product::TYPE_DIGITAL, Product::TYPE_PHYSICAL])],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0', 'gte:min_price'],
            'platforms' => ['nullable', 'array'],
            'platforms.*' => ['string', 'max:120'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['string', 'max:120'],
            'sort' => ['nullable', Rule::in(['price_asc', 'price_desc', 'newest', 'oldest'])],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:48'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
