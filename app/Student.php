<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // ログをインポート
use App\SchoolGrade; // SchoolGrade クラスのインポート
use App\Grade; // Grade クラスのインポート

class Student extends Model
{
    protected $fillable = [
        'grade', 'name', 'email', 'address', 'img_path', 'comment'
    ];

    // スコープ: 名前でフィルタリング
    public function scopeFilterByName($query, $name)
    {
        if (!empty($name)) {
            return $query->where('name', 'like', '%' . $name . '%');
        }
        return $query;
    }

    // スコープ: 成績でフィルタリング
    public function scopeFilterByGrade($query, $grade)
    {
        if (!empty($grade)) {
            return $query->where('grade', $grade);
        }
        return $query;
    }

    // 画像の保存
    public function savePhoto($file)
    {
        try {
            if ($this->img_path) {
                Storage::disk('public')->delete($this->img_path);
            }
            $path = $file->store('photos', 'public');
            $this->img_path = $path;
            $this->save();
        } catch (\Exception $e) {
            Log::error('File Upload Error in savePhoto: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    // 画像の削除
    public function deletePhoto()
    {
        try {
            if ($this->img_path) {
                Storage::disk('public')->delete($this->img_path);
                $this->img_path = null;
                $this->save();
            }
        } catch (\Exception $e) {
            Log::error('File Deletion Error in deletePhoto: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    // 学生情報の保存処理を含むメソッド
    public function saveStudent(array $data, $file = null)
    {
        $this->fill($data);
        if ($file) {
            $this->savePhoto($file);
        } else {
            $this->save();
        }
    }

    // SchoolGrade リレーションシップ
    public function schoolGrades()
    {
        return $this->hasMany(SchoolGrade::class);
    }

    // Grade リレーションシップ
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
