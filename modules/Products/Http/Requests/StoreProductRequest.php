<?php

namespace Modules\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            /* 'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'barcode' => 'nullable|string|unique:products,barcode',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'required|integer|min:0|gt:min_stock',
            'unit' => 'integer',
            'allergens' => 'nullable|array',
            'nutritional_info' => 'nullable|array',
            'image' => 'nullable|string',
            'preparation_time' => 'required|integer|min:0',
            'availability' => 'sometimes',
            'status' => 'sometimes' */
        ];
    }

    public function messages()
    {
        return [
            /* 'name.required' => 'El nombre del producto es obligatorio',
            'price.required' => 'El precio es obligatorio',
            'category_id.required' => 'La categoría es obligatoria',
            'category_id.exists' => 'La categoría seleccionada no existe',
            'max_stock.gt' => 'El stock máximo debe ser mayor al stock mínimo'*/
        ];
    }
}