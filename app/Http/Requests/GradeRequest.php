<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GradeRequest extends FormRequest
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
            'subject' => 'required|string|max:255',
            'score' => 'required|numeric|min:0|max:100',
        ];
    }
}
