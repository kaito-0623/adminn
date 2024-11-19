<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'subject',
        'score',
    ];

    // Studentとのリレーションシップを定義
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}





