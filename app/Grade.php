<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Student; // Student クラスのインポート

class Grade extends Model
{
    protected $fillable = [
        'student_id', 'subject', 'score', 'grade', 'term', 'name' // 追加
    ];

    // Studentとのリレーションシップを定義
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
