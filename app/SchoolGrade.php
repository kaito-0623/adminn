<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

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
