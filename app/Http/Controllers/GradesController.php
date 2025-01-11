<?php

namespace App\Http\Controllers;

use App\Grade;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Log クラスをインポート

class GradesController extends Controller
{
    public function index()
    {
        $grades = Grade::all();
        return view('grades.index', compact('grades'));
    }

    public function create(Request $request)
    {
        // 学生IDを取得
        $student_id = $request->query('student_id'); // クエリパラメータから student_id を取得
        Log::info('Create method called. Student ID:', ['student_id' => $student_id]);
        return view('grades.create', compact('student_id'));
    }

    public function store(Request $request)
    {
        Log::info('Store method called.');

        // フォームデータのログ
        Log::info('Request data:', $request->all());

        // データのバリデーション
        try {
            $validatedData = $request->validate([
                'student_id' => 'required|exists:students,id',
                'grade' => 'required|string|max:255',
                'term' => 'required|string|max:255',
                'subject' => 'required|string|max:255',
                'score' => 'required|numeric|min:0|max:100',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', $e->errors());
            return redirect()->back()->withErrors($e->errors());
        }

        Log::info('Validation passed.', $validatedData);

        // 成績の作成
        try {
            $grade = Grade::create([
                'student_id' => $validatedData['student_id'],
                'subject' => $validatedData['subject'],
                'score' => $validatedData['score'],
                'grade' => $validatedData['grade'],
                'term' => $validatedData['term']
            ]);

            Log::info('Grade created:', $grade->toArray());
        } catch (\Exception $e) {
            Log::error('Error creating grade:', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', '成績の登録に失敗しました。');
        }

        // 学生詳細表示画面にリダイレクト
        return redirect()->route('students.show', $validatedData['student_id'])->with('success', '成績が追加されました');
    }

    public function show($id)
    {
        Log::info('Show method called.');
        
        $grade = Grade::findOrFail($id);
        return view('grades.show', compact('grade'));
    }

    public function edit($id)
    {
        Log::info('Edit method called.');
        
        $grade = Grade::findOrFail($id);
        return view('grades.edit', compact('grade'));
    }

    public function update(Request $request, $id)
    {
        Log::info('Update method called.');
        
        // データのバリデーション
        $validatedData = $request->validate([
            'subject' => 'required|string|max:255',
            'score' => 'required|numeric|min:0|max:100',
            'grade' => 'required|string|max:255',
            'term' => 'required|string|max:255',
        ]);

        Log::info('Validation passed for update.', $validatedData);

        // 成績の更新
        $grade = Grade::findOrFail($id);
        $grade->update($validatedData);

        Log::info('Grade updated:', $grade->toArray());

        return redirect()->route('grades.index')->with('success', '成績が更新されました');
    }

    public function destroy($id)
    {
        Log::info('Destroy method called.');
        
        // 成績の削除
        $grade = Grade::findOrFail($id);
        $grade->delete();

        Log::info('Grade deleted:', ['id' => $id]);

        return redirect()->route('grades.index')->with('success', '成績が削除されました');
    }

    public function updateGrades() // 学年更新機能
    {
        Log::info('Update grades method called.');

        // すべての学生の学年を1つ上げる
        Student::all()->each(function($student) {
            $student->grade += 1;
            $student->save();

            Log::info('Student grade updated:', ['student_id' => $student->id, 'new_grade' => $student->grade]);
        });

        return redirect()->route('menu.index')->with('success', '学年が更新されました');
    }
}
