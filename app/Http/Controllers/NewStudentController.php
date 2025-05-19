<?php

namespace App\Http\Controllers;

use App\SchoolGrade;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewStudentController extends Controller
{
    /**
     * 成績をフィルタリングするメソッド。
     */
    public function filterStudentGrades(Request $request, Student $student)
    {
        // モデルのメソッドを使って成績を取得
        try {
            $grades = SchoolGrade::getFilteredAndSortedGrades(
                $student->id,
                $request->input('grade'), // 学年フィルタ
                $request->input('term')  // 学期フィルタ
            );

            return view('students.partials.grades_table', compact('grades'));
        } catch (\Exception $e) {
            // エラーハンドリングとログ出力
            Log::error('Error while filtering student grades.', [
                'student_id' => $student->id,
                'error_message' => $e->getMessage()
            ]);

            return response()->json(['error' => '成績の取得中にエラーが発生しました。'], 500);
        }
    }

    /**
     * 成績を並べ替えるメソッド。
     */
    public function sortStudentGrades(Request $request, Student $student)
    {
        // モデルのメソッドを使って成績を取得
        try {
            $grades = SchoolGrade::getFilteredAndSortedGrades(
                $student->id,
                null, // 学年フィルタなし
                null, // 学期フィルタなし
                $request->input('order', 'asc') // 並べ替え条件
            );
            
            return view('students.partials.grades_table', compact('grades'));
        } catch (\Exception $e) {
            // エラーハンドリングとログ出力
            Log::error('Error while sorting student grades.', [
                'student_id' => $student->id,
                'error_message' => $e->getMessage()
            ]);

            return response()->json(['error' => '成績のソート中にエラーが発生しました。'], 500);
        }
    }
}