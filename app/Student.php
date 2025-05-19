<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Student extends Model
{
    protected $fillable = ['name', 'address', 'email', 'grade', 'img_path', 'comment'];

    // 学生が持つ成績とのリレーションを定義  showメソッドに紐づく
    public function schoolGrades()
    {
        return $this->hasMany(SchoolGrade::class);
    }

    /**
 * 指定されたIDの学生情報と紐づく成績データを取得
 * 
 * @param int $id 学生ID
 * @return array|null 学生データと成績データ（エラー時はnull）
 */
public static function getStudentWithGrades($id) //showメソッドに紐づく
{
    try {
        // 学生情報を取得
        $student = self::findOrFail($id);
        
        // 学生に紐づく成績データを取得（リレーション）
        $grades = $student->schoolGrades;

        // compact() を使って学生データと成績データを一括返却
        return compact('student', 'grades'); 
    } catch (\Exception $e) {
        // エラー時のログ記録
        Log::error('Error retrieving student details or grades.', [
            'student_id' => $id,
            'error_message' => $e->getMessage(),
        ]);

        return null; // エラー時は `null` を返却
    }
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
            
            return $student;
        } catch (\Exception $e) {
            Log::error('Error creating student.', ['data' => $data, 'error_message' => $e->getMessage()]);
            throw new \Exception('Failed to create student: ' . $e->getMessage());
        }
    }

    
    public static function getStudentById($id) //editメソッドに紐づく
    {
        try {
            // 学生情報を取得
            $student = self::findOrFail($id);

            return $student;
        } 
        catch (\Exception $e) {
            Log::error('Error retrieving student details.', [
            'student_id' => $id,
            'error_message' => $e->getMessage(),
            ]);
            return null; // エラー時は `null`
        }
    }



    // 学生情報を更新するメソッド
    public function storePhoto($photo) //画像処理に集中
{
    return $photo->store('photos', 'public');
}

public function updateStudent(array $data, $photo = null) //データ更新のみに集中
{
    try {
        if ($photo) {
            $data['img_path'] = $this->storePhoto($photo); //`storePhoto()` を使う
        }
        
        $this->update($data);
        
        return $this;
    } catch (\Exception $e) {
        Log::error('Error updating student.', ['id' => $this->id, 'error_message' => $e->getMessage()]);
        return null; // **エラー時に `null` を返す**
    }
}

  //学年を一括更新するメソッド
public static function updateAllGrades()
{
    try {
        //1～4年生の学年を更新
        self::where('grade', '<', 5)->increment('grade', 1);  

        //5年生を「卒業生」として `status` を変更
        self::where('grade', '=', 5)->update(['status' => '卒業']);

    } catch (\Exception $e) {
        Log::error('学年更新エラー', ['error_message' => $e->getMessage()]);
        throw new \Exception('学年更新に失敗しました: ' . $e->getMessage());
    }
}


    // **追加: 学生削除のカプセル化**
    public static function deleteStudent($id)
    {
        try {
            $student = self::find($id);

            if (!$student) {
                Log::warning('Student not found for deletion.', ['student_id' => $id]);
                return false;
            }

            $student->delete();

            return true;
        } catch (\Exception $e) {
            Log::error('Error deleting student.', ['student_id' => $id, 'error_message' => $e->getMessage()]);
            throw new \Exception('Failed to delete student: ' . $e->getMessage());
        }
    }
}