<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Grade; // 名前空間の修正

class Student extends Model
{
    protected $fillable = [
        'grade', 'name', 'email', 'address', 'img_path', 'comment'
    ];

    public function schoolGrades()
    {
        return $this->hasMany(SchoolGrade::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}

