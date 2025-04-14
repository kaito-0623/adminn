<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Student extends Model
{
    protected $fillable = ['name', 'address', 'email', 'grade', 'img_path', 'comment'];

    // 学生が持つ成績とのリレーションを定義
    public function schoolGrades()
    {
        return $this->hasMany(SchoolGrade::class);
    }

    // 名前でフィルタリングするスコープ
    public function scopeFilterByName($query, $name)
    {
        if (!empty($name)) {
            return $query->where('name', 'like', '%' . $name . '%');
        }
        return $query;
    }

    // 学年でフィルタリングするスコープ
    public function scopeFilterByGrade($query, $grade)
    {
        if (!empty($grade)) {
            return $query->where('grade', $grade);
        }
        return $query;
    }

    // 学生作成ロジック
    public static function createStudent(array $data, $photo = null)
    {
        try {
            $data['img_path'] = $photo ? $photo->store('photos', 'public') : null;
            $student = self::create($data);
            Log::info('Student created successfully.', ['id' => $student->id, 'data' => $data]);
            return $student;
        } catch (\Exception $e) {
            Log::error('Error creating student.', ['data' => $data, 'error_message' => $e->getMessage()]);
            throw new \Exception('Failed to create student: ' . $e->getMessage());
        }
    }

    // 学生情報を更新するメソッド
    public function updateStudent(array $data, $photo = null)
    {
        try {
            if ($photo) {
                $data['img_path'] = $photo->store('photos', 'public');
            }
            $this->update($data);
            Log::info('Student updated successfully.', ['id' => $this->id, 'data' => $data]);
            return $this;
        } catch (\Exception $e) {
            Log::error('Error updating student.', ['id' => $this->id, 'error_message' => $e->getMessage()]);
            throw new \Exception('Failed to update student: ' . $e->getMessage());
        }
    }

    // 学年を更新するロジック
    public static function updateGrades()
    {
        try {
            self::where('grade', '<', 4)->increment('grade', 1); // 一括更新処理
            Log::info('Grades updated successfully for all students.');
        } catch (\Exception $e) {
            Log::error('Error updating grades.', ['error_message' => $e->getMessage()]);
            throw new \Exception('Failed to update grades: ' . $e->getMessage());
        }
    }
}