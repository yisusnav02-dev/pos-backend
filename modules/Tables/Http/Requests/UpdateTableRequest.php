<?php

namespace Modules\Tables\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTableRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $tableId = $this->route('table');

        return [
            'name' => 'sometimes|string|max:255',
            'code' => [
                'sometimes',
                'string',
                Rule::unique('tables')->ignore($tableId)
            ],
            'capacity' => 'sometimes|integer|min:1|max:20',
            'type' => 'sometimes|in:indoor,outdoor,terrace,vip',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'min_consumption' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer',
            'coordinates' => 'nullable|array',
            'status' => 'sometimes|in:available,occupied,reserved,maintenance'
        ];
    }
}