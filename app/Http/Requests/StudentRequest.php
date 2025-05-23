<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // 必要に応じて変更可能
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:students,email,' . $this->route('student'),
            ],
            'grade' => 'required|integer|min:1|max:12', 
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'comment' => 'nullable|string|max:500', 
        ];
    }
}