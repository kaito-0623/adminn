<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolGradeRequest;
use App\SchoolGrade;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SchoolGradeController extends Controller
{
    // 成績登録画面を表示するメソッド
    public function createForm(Request $request)
    {
        Log::info('Create method called.');
        $student = Student::findOrFail($request->student_id);
        return view('schoolGrades.create', compact('student'));
    }

    // 成績情報を保存するメソッド
    public function store(SchoolGradeRequest $request)
    {
        Log::info('Store method called.');

        try {
            $schoolGrade = new SchoolGrade();
            $schoolGrade->fill($request->validated());
            $schoolGrade->save();

            Log::info('School grade data saved:', $schoolGrade->toArray());
            return redirect()->route('students.show', $request->student_id)->with('success', '成績が登録されました');
        } catch (\Exception $e) {
            Log::error('An error occurred in the store method: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', '成績の登録に失敗しました。');
        }
    }

    // 成績編集画面を表示するメソッド
    public function editStudentGradeForm(SchoolGrade $schoolGrade)
    {
        Log::info('Edit method called.');
        $students = Student::all(); // すべての学生データを取得
        return view('schoolGrades.edit', compact('schoolGrade', 'students'));
    }

    // 成績情報を更新するメソッド
    public function update(SchoolGradeRequest $request, SchoolGrade $schoolGrade)
    {
        Log::info('Update method called.');

        try {
            $schoolGrade->update($request->validated());
            Log::info('School grade data updated:', $schoolGrade->toArray());
            return redirect()->route('students.show', $schoolGrade->student_id)->with('success', '成績が更新されました');
        } catch (\Exception $e) {
            Log::error('An error occurred in the update method: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', '成績の更新に失敗しました。');
        }
    }

    // 学年を更新するメソッド
    public function updateStudentGrades()
    {
        Log::info('Update Grades method called.');

        try {
            SchoolGrade::updateStudentGrades();
            return redirect()->route('menu.index')->with('success', '学年が更新されました。');
        } catch (\Exception $e) {
            Log::error('An error occurred in the updateGrades method: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', '学年の更新に失敗しました。');
        }
    }
}
