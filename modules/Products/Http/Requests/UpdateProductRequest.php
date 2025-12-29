<?php

namespace Modules\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $productId = $this->route('product');

        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'sku' => [
                'nullable',
                'string',
                Rule::unique('products')->ignore($productId)
            ],
            'barcode' => [
                'nullable',
                'string',
                Rule::unique('products')->ignore($productId)
            ],
            'category_id' => 'sometimes|exists:categories,id',
            'stock' => 'sometimes|integer|min:0',
            'min_stock' => 'sometimes|integer|min:0',
            'max_stock' => 'sometimes|integer|min:0|gt:min_stock',
            'unit' => 'sometimes',
            'allergens' => 'nullable|array',
            'nutritional_info' => 'nullable|array',
            'image' => 'nullable|string',
            'preparation_time' => 'sometimes|integer|min:0',
            'availability' => 'sometimes',
            'status' => 'sometimes'
        ];
    }
}