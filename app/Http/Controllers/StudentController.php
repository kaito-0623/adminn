<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Student;
use App\SchoolGrade;

class StudentController extends Controller
{
    // 学生一覧を表示するメソッド
    public function index(Request $request)
    {
        Log::info('Index method accessed for students.');

        // ソート条件を取得（デフォルトは昇順）
        $sortOrder = $request->input('sortOrder', 'asc');

        // フィルタリング（名前や学年を利用）＋ ソート条件適用
        $students = Student::query()
            ->filterByName($request->input('name')) // 名前でフィルタリング
            ->filterByGrade($request->input('grade')) // 学年でフィルタリング
            ->orderBy('grade', $sortOrder) // 学年でソート
            ->get();

        Log::info('Students retrieved successfully.', ['student_count' => $students->count()]);

        // 学生一覧ビューを返す
        return view('students.index', compact('students', 'sortOrder'));
    }

    // 学生作成画面を表示するメソッド
    public function create()
    {
        Log::info('Create method accessed for students.');
        return view('students.create'); // ビューを返す
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:255', // 住所のバリデーションを追加
        'email' => 'required|email|max:255',
        'grade' => 'required|integer|min:1|max:12',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 写真のバリデーション
        'comment' => 'nullable|string|max:500', // コメントのバリデーション
    ]);

    try {
        // 写真をアップロード
        $photoPath = $request->file('photo') ? $request->file('photo')->store('photos', 'public') : null;

        // 学生データを作成
        $student = Student::create([
            'name' => $request->name,
            'address' => $request->address, // 住所を保存する
            'email' => $request->email,
            'grade' => $request->grade,
            'img_path' => $photoPath,
            'comment' => $request->comment,
        ]);

        Log::info('Student created successfully.', ['student_id' => $student->id]);

        // 作成完了後、詳細画面にリダイレクト
        return redirect()->route('students.show', $student->id)->with('success', '学生情報が登録されました。');
    } catch (\Exception $e) {
        Log::error('Error creating student.', ['error_message' => $e->getMessage()]);
        return redirect()->route('students.index')->with('error', '学生情報の登録中にエラーが発生しました。');
    }
}

    // 学生詳細を表示するメソッド
    public function show($id)
    {
        try {
            // 指定されたIDの学生を取得
            $student = Student::findOrFail($id);

            // 学生に紐づいた成績データを取得
            $grades = $student->schoolGrades;

            Log::info('Show method accessed for student and grades.', [
                'student_id' => $id,
                'grades_count' => $grades->count(),
            ]);

            // 学生データと成績データをビューに渡す
            return view('students.show', compact('student', 'grades'));
        } catch (\Exception $e) {
            Log::error('Error retrieving student details or grades.', [
                'student_id' => $id,
                'error_message' => $e->getMessage(),
            ]);
            return redirect()->route('students.index')->with('error', '学生詳細を取得できませんでした。');
        }
    }

    // 学生情報を更新するメソッド
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'grade' => 'required|integer|min:1|max:12',
            'comment' => 'nullable|string|max:500',
        ]);

        try {
            $student = Student::findOrFail($id);

            // 更新可能なフィールドのみを使用
            $student->update($validatedData);

            Log::info('Student updated successfully.', ['student_id' => $id]);

            // 更新完了後、学生詳細画面にリダイレクト
            return redirect()->route('students.show', $student->id)->with('success', '学生情報が更新されました。');
        } catch (\Exception $e) {
            Log::error('Error updating student.', ['student_id' => $id, 'error_message' => $e->getMessage()]);
            return redirect()->route('students.index')->with('error', '学生情報の更新中にエラーが発生しました。');
        }
    }

    // 学生情報を編集する画面を表示するメソッド
    public function edit($id)
    {
        try {
            // 指定されたIDの学生を取得
            $student = Student::findOrFail($id);

            Log::info('Edit form accessed for student.', ['student_id' => $id]);

            // 編集フォームを表示
            return view('students.edit', compact('student'));
        } catch (\Exception $e) {
            Log::error('Error accessing edit form.', ['student_id' => $id, 'error_message' => $e->getMessage()]);
            return redirect()->route('students.index')->with('error', '編集画面の表示中にエラーが発生しました。');
        }
    }

    // 全ての学生の学年を一括更新するメソッド
    public function updateAllGrades()
    {
        try {
            // 全ての学生を取得
            $students = Student::all();

            foreach ($students as $student) {
                if ($student->grade !== '卒業生') {
                    // 学年を1つ増加。ただし、4を超えたら「卒業生」に設定
                    $newGrade = $student->grade < 4 ? $student->grade + 1 : '卒業生';
                    $student->update(['grade' => $newGrade]);
                }
            }

            Log::info('All student grades updated successfully.');
            return redirect()->route('students.index')->with('success', '全ての学生の学年が更新されました。');
        } catch (\Exception $e) {
            Log::error('Error updating all student grades.', ['error_message' => $e->getMessage()]);
            return redirect()->route('students.index')->with('error', '学年更新中にエラーが発生しました。');
        }
    }

    // 学生情報を削除するメソッド
    public function destroy($id)
    {
        try {
            // モデルのメソッドを利用して削除処理を委譲
            Student::deleteStudent($id);
            return redirect()->route('students.index')->with('success', '学生が削除されました。');
        } catch (\Exception $e) {
            Log::error('Error deleting student.', ['student_id' => $id, 'error_message' => $e->getMessage()]);
            return redirect()->route('students.index')->with('error', '学生の削除中にエラーが発生しました。');
        }
    }
}