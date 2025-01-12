<?php

namespace App\Http\Controllers;

use App\SchoolGrade;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SchoolGradeController extends Controller
{
    // 成績登録画面を表示するメソッド
    public function create(Request $request)
    {
        Log::info('Create method called.');
        // 学生IDを取得し、学生データを検索
        $student = Student::findOrFail($request->student_id);
        return view('schoolGrades.create', compact('student'));
    }

    // 成績情報を保存するメソッド
    public function store(Request $request)
    {
        Log::info('Store method called.');
        Log::info('Request data:', $request->all()); // リクエストデータのログ

        try {
            Log::info('Starting validation.');
            // バリデーション
            $validated = $request->validate([
                'student_id' => 'required|exists:students,id',
                'grade' => 'required|string|max:255',
                'term' => 'required|string|max:255',
                'japanese' => 'required|integer|min:0|max:100',
                'math' => 'required|integer|min:0|max:100',
                'science' => 'required|integer|min:0|max:100',
                'social_studies' => 'required|integer|min:0|max:100',
                'music' => 'required|integer|min:0|max:100',
                'home_economics' => 'required|integer|min:0|max:100',
                'english' => 'required|integer|min:0|max:100',
                'art' => 'required|integer|min:0|max:100',
                'health_and_physical_education' => 'required|integer|min:0|max:100',
            ]);

            Log::info('Validation passed.', $validated);

            Log::info('Checking for existing school grade.');
            // 既存の成績データがあるかどうかを確認
            $existingSchoolGrade = SchoolGrade::where('student_id', $request->student_id)
                                              ->where('grade', $request->grade)
                                              ->where('term', $request->term)
                                              ->first();

            if ($existingSchoolGrade) {
                Log::info('Existing school grade found, updating data.');
                // 既存の成績データがある場合は更新
                $existingSchoolGrade->update($request->all());
                Log::info('School grade data updated:', $existingSchoolGrade->toArray());
            } else {
                Log::info('No existing school grade found, creating new data.');
                // 既存の成績データがない場合は新しく作成
                $schoolGrade = new SchoolGrade();
                Log::info('Filling school grade data.');
                $schoolGrade->fill($request->all());
                $schoolGrade->save();
                Log::info('School grade data saved:', $schoolGrade->toArray());
            }

            Log::info('Store method completed.');
            return redirect()->route('students.show', $request->student_id)->with('success', '成績が登録されました');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed: ' . $e->getMessage());
            Log::error('Validation errors: ' . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('An error occurred in the store method: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', '成績の登録に失敗しました。');
        }
    }

    // 成績編集画面を表示するメソッド
    public function edit(SchoolGrade $schoolGrade)
    {
        Log::info('Edit method called.');
        $students = Student::all(); // すべての学生データを取得
        return view('schoolGrades.edit', compact('schoolGrade', 'students'));
    }

    // 成績情報を更新するメソッド
    public function update(Request $request, SchoolGrade $schoolGrade)
    {
        Log::info('Update method called.');
        // バリデーション
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'grade' => 'required|string|max:255',
            'term' => 'required|string|max:255',
            'japanese' => 'required|integer|min:0|max:100',
            'math' => 'required|integer|min:0|max:100',
            'science' => 'required|integer|min:0|max:100',
            'social_studies' => 'required|integer|min:0|max:100',
            'music' => 'required|integer|min:0|max:100',
            'home_economics' => 'required|integer|min:0|max:100',
            'english' => 'required|integer|min:0|max:100',
            'art' => 'required|integer|min:0|max:100',
            'health_and_physical_education' => 'required|integer|min:0|max:100',
        ]);

        Log::info('Updating school grade data.');
        // フォームデータを更新する
        $schoolGrade->update($request->all());

        Log::info('School grade data updated:', $schoolGrade->toArray());
        return redirect()->route('students.show', $schoolGrade->student_id)->with('success', '成績が更新されました');
    }

    // 学年を更新するメソッド
    public function updateGrades()
    {
        Log::info('Update Grades method called.');
        
        // 例として、すべての学生の学年を1年進める処理を実装
        $students = Student::all();
        foreach ($students as $student) {
            switch ($student->grade) {
                case '1年生':
                    $student->grade = '2年生';
                    break;
                case '2年生':
                    $student->grade = '3年生';
                    break;
                case '3年生':
                    $student->grade = '4年生';
                    break;
                case '4年生':
                    $student->grade = '卒業生'; // 例: 4年生の次は卒業生とする
                    break;
            }
            $student->save();
        }

        return redirect()->route('menu.index')->with('success', '学年が更新されました。');
    }
}
