<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'email|unique:users,email',
            'phone_number' => 'required|string|unique:users,phone_number',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'email.unique' => 'El email ya está registrado',
            'phone_number.required' => 'El número de teléfono es obligatorio',
            'phone_number.unique' => 'El número de teléfono ya está registrado',
        ];
    }
}