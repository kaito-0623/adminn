<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        // 学生情報作成
        $data['img_path'] = $photo ? $photo->store('photos', 'public') : null;
        return self::create($data);
    }

    // 学生情報を更新するメソッド
    public function updateStudent(array $data, $photo = null)
    {
        if ($photo) {
            $data['img_path'] = $photo->store('photos', 'public');
        }
        return $this->update($data);
    }

    // 学年を更新するロジック
    public static function updateGrades()
    {
        // クエリを直接実行して一括更新（N+1 問題を回避）
        self::where('grade', '<', 4)->increment('grade', 1);
    }
}