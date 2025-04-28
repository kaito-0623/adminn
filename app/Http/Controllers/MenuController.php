<?php

namespace App\Http\Controllers;

use App\Student; // モデルをインポート

class MenuController extends Controller
{
    public function index()
    {
        // 1人の学生データを取得 (例: 最初の学生を取得)
        $student = Student::first(); // 学生データ取得ロジックを調整可能

        // ビューにデータを渡して表示
        return view('menu.index', compact('student'));
    }
}