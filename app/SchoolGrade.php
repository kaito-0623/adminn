<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class SchoolGrade extends Model
{
    protected $fillable = [
        'student_id', 'grade', 'term',
        'japanese', 'math', 'science', 'social_studies', 'music',
        'home_economics', 'english', 'art', 'health_and_physical_education',
        'subject', 'score' // Gradeモデルから統合したフィールド
    ];

    // 学生とのリレーション
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // 学年をラベル形式に変換するアクセサ
    public function getGradeLabelAttribute()
    {
        $grades = [
            1 => '1年生',
            2 => '2年生',
            3 => '3年生',
            4 => '4年生',
        ];

        return $grades[$this->grade] ?? '学年不明';
    }

    // スコープ: 学年でフィルタリング
    public function scopeFilterByGrade($query, $grade)
    {
        $gradeMap = [
            '1年生' => 1,
            '2年生' => 2,
            '3年生' => 3,
            '4年生' => 4,
        ];
        if (!empty($grade)) {
            return $query->where('grade', $gradeMap[$grade] ?? $grade);
        }
        return $query;
    }

    // スコープ: 学期でフィルタリング
    public function scopeFilterByTerm($query, $term)
    {
        $termMap = [
            '1学期' => '1学期',
            '2学期' => '2学期',
            '3学期' => '3学期',
        ];
        if (!empty($term)) {
            return $query->where('term', $termMap[$term] ?? $term);
        }
        return $query;
    }

    // スコープ: 学年で並べ替え
    public function scopeOrderByGrade($query, $order = 'asc')
    {
        return $query->orderBy('grade', $order);
    }

    // 成績検索メソッド
    public static function searchGrades($studentId, $grade = null, $term = null, $order = 'asc')
    {
        try {
            $query = self::query()->where('student_id', $studentId);

            if (!empty($grade)) {
                $query->filterByGrade($grade);
            }

            if (!empty($term)) {
                $query->filterByTerm($term);
            }

            $query->orderByGrade($order);

            return $query->get();
        } catch (\Exception $e) {
            Log::error('Error retrieving grades.', [
                'error_message' => $e->getMessage(),
            ]);
            throw new \Exception('Failed to retrieve grades: ' . $e->getMessage());
        }
    }

    // 成績作成メソッド
    public static function createGrade(array $data)
    {
        return self::create($data);
    }

    // 成績更新メソッド
    public static function updateGrade($id, array $data)
    {
        $grade = self::findOrFail($id);
        $grade->update($data);
        return $grade;
    }

    // 成績削除メソッド
    public static function deleteGrade($id)
    {
        $grade = self::findOrFail($id);
        $grade->delete();
        return true;
    }
}