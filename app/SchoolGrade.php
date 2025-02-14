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

    // 学年を更新するビジネスロジック
    public static function updateStudentGrades()
    {
        $students = Student::with('schoolGrades')->get(); // Eager Loading で N+1 問題を解消

        foreach ($students as $student) {
            foreach ($student->schoolGrades as $grade) {
                switch ($grade->grade) {
                    case '1年生':
                        $grade->grade = '2年生';
                        break;
                    case '2年生':
                        $grade->grade = '3年生';
                        break;
                    case '3年生':
                        $grade->grade = '4年生';
                        break;
                    case '4年生':
                        $grade->grade = '卒業生'; // 例: 4年生の次は卒業生とする
                        break;
                }
                $grade->save();
            }
        }
    }

    // 成績を保存または更新するビジネスロジック
    public static function saveOrUpdate($data)
    {
        $existingSchoolGrade = self::where('student_id', $data['student_id'])
                                    ->where('grade', $data['grade'])
                                    ->where('term', $data['term'])
                                    ->first();

        if ($existingSchoolGrade) {
            $existingSchoolGrade->update($data);
        } else {
            self::create($data);
        }
    }
}
