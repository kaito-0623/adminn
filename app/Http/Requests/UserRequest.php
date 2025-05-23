<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true; // 必要に応じて認可を変更
    }

    public function rules()
    {
        return [
            'user_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $this->route('user'),
            ],
        ];
    }
}