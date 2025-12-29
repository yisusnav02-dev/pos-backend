<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->route('user');

        return [
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')->ignore($userId)
            ],
            'phone_number' => [
                'sometimes',
                'string',
                Rule::unique('users')->ignore($userId)
            ],
            'password' => 'sometimes|string|min:8|confirmed',
            'role' => 'sometimes|in:admin,manager,waiter',
            'status' => 'sometimes|in:active,inactive'
        ];
    }
}