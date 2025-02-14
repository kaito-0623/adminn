<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Student;

class Grade extends Model
{
    protected $fillable = [
        'student_id', 'subject', 'score', 'grade', 'term', 'name'
    ];

    // Studentとのリレーションシップを定義
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // 成績を保存または更新するビジネスロジック
    public static function saveOrUpdate($data)
    {
        $existingGrade = self::where('student_id', $data['student_id'])
                             ->where('subject', $data['subject'])
                             ->where('term', $data['term'])
                             ->first();

        if ($existingGrade) {
            $existingGrade->update($data);
        } else {
            self::create($data);
        }
    }
}
