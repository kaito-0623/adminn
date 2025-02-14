<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolGradeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_id' => 'required|exists:students,id',
            'grade' => 'required|string|max:255',
            'term' => 'required|string|max:255',
            'japanese' => 'required|integer|min:0|max:100',
            'math' => 'required|integer|min:0|max:100',
            'science' => 'required|integer|min:0|max:100',
            'social_studies' => 'required|integer|min:0|max:100',
            'music' => 'required|integer|min:0|max:100',
            'home_economics' => 'required|integer|min:0|max:100',
            'english' => 'required|integer|min:0|max:100',
            'art' => 'required|integer|min:0|max:100',
            'health_and_physical_education' => 'required|integer|min:0|max:100',
        ];
    }
}
