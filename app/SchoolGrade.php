<?php

namespace App; // 名前空間の修正

use Illuminate\Database\Eloquent\Model;
use App\Student; // Student モデルのインポート

class SchoolGrade extends Model
{
    protected $fillable = [
        'student_id', 'grade', 'term', 'japanese', 'math', 'science', 'social_studies', 'music', 'home_economics', 'english', 'art', 'health_and_physical_education'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}


