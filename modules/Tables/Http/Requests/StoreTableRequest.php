<?php

namespace Modules\Tables\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTableRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            /* 'name' => 'required|string|max:255',
            'code' => 'required|string|unique:tables,code',
            'capacity' => 'required|integer|min:1|max:20',
            'type' => 'required|in:indoor,outdoor,terrace,vip',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'min_consumption' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer',
            'coordinates' => 'nullable|array',
            'status' => 'sometimes|in:available,occupied,reserved,maintenance' */
        ];
    }

    public function messages()
    {
        return [
            /* 'name.required' => 'El nombre de la mesa es obligatorio',
            'code.required' => 'El código de la mesa es obligatorio',
            'code.unique' => 'El código de la mesa ya existe',
            'capacity.required' => 'La capacidad es obligatoria',
            'capacity.min' => 'La capacidad mínima es 1 persona',
            'capacity.max' => 'La capacidad máxima es 20 personas',
            'type.required' => 'El tipo de mesa es obligatorio',
            'type.in' => 'El tipo debe ser: indoor, outdoor, terrace o vip' */
        ];
    }
}