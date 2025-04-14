<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Student;

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

    // 学年を更新するメソッド
    public function updateStudentGrades()
    {
        try {
            Log::info('UpdateStudentGrades method started.');

            // モデルのビジネスロジックを呼び出し
            Student::updateGrades();

            Log::info('UpdateStudentGrades method completed.');
            return redirect()->route('menu.index')->with('success', '学年が更新されました。');
        } catch (\Exception $e) {
            Log::error('Error occurred while updating student grades.', ['error_message' => $e->getMessage()]);
            return redirect()->route('menu.index')->with('error', '学年更新に失敗しました。');
        }
    }

    // 学生登録画面を表示するメソッド
    public function create()
    {
        Log::info('Create method accessed for students.');

        try {
            $view = view('students.create');
            Log::info('Response being returned for students.create.', ['view_name' => $view->getName()]);
            return $view;
        } catch (\Exception $e) {
            Log::error('Error occurred while accessing students.create.', ['error_message' => $e->getMessage()]);
            return redirect()->route('students.index')->with('error', '学生登録画面の表示に失敗しました。');
        }
    }

    // 学生情報を保存するメソッド
    public function store(StudentRequest $request)
    {
        Log::info('Store method accessed for students.');

        try {
            // バリデーション済みデータを取得
            $validated = $request->validated();

            // モデルのビジネスロジックを呼び出し
            $photo = $request->hasFile('photo') ? $request->file('photo') : null;
            $student = Student::createStudent($validated, $photo);

            Log::info('Student saved successfully.', ['student_id' => $student->id]);
            return redirect()->route('students.index')->with('success', '学生が正常に登録されました！');
        } catch (\Exception $e) {
            Log::error('Error occurred while storing student.', ['error_message' => $e->getMessage()]);
            return redirect()->route('students.create')->with('error', '学生の登録中にエラーが発生しました。');
        }
    }

    // 学生情報を更新するメソッド
    public function update(StudentRequest $request, $id)
    {
        Log::info('Update method accessed.', ['student_id' => $id]);

        $student = Student::find($id);

        if (!$student) {
            Log::warning('Student not found for update.', ['student_id' => $id]);
            return redirect()->route('students.index')->with('error', '指定された学生が見つかりません。');
        }

        try {
            // バリデーション済みデータを取得
            $validated = $request->validated();

            // モデルのビジネスロジックを呼び出し
            $photo = $request->hasFile('photo') ? $request->file('photo') : null;
            $student->updateStudent($validated, $photo);

            Log::info('Student updated successfully.', ['student_id' => $student->id]);
            return redirect()->route('students.show', $student->id)->with('success', '学生情報が正常に更新されました。');
        } catch (\Exception $e) {
            Log::error('Error occurred while updating student.', ['error_message' => $e->getMessage()]);
            return redirect()->back()->with('error', '学生情報の更新に失敗しました。');
        }
    }

    // 学生編集画面を表示するメソッド
    public function edit($id)
    {
        Log::info('Edit method accessed.', ['student_id' => $id]);

        $student = Student::find($id);

        if (!$student) {
            Log::warning('Student not found for editing.', ['student_id' => $id]);
            return redirect()->route('students.index')->with('error', '学生が見つかりませんでした。');
        }

        Log::info('Student data retrieved for editing.', ['student_id' => $student->id]);
        return view('students.edit', compact('student'));
    }

    // 学生詳細を表示するメソッド
    public function show($id)
    {
        Log::info('Show method accessed.', ['student_id' => $id]);

        // 学生データを取得（成績リレーション込み）
        $student = Student::with('schoolGrades')->find($id);

        if (!$student) {
            Log::warning('Student not found.', ['student_id' => $id]);
            return redirect()->route('students.index')->with('error', '学生が見つかりませんでした。');
        }

        // 学生に紐づく成績データを取得
        $grades = $student->schoolGrades;

        Log::info('Student and grades retrieved successfully.', [
            'student_id' => $student->id,
            'grades_count' => $grades->count(),
        ]);

        // ビューにデータを渡す
        return view('students.show', compact('student', 'grades'));
    }

    // 学生を削除するメソッド
    public function destroy($id)
    {
        try {
            Log::info('Destroy method accessed.', ['student_id' => $id]);

            $student = Student::find($id);

            if (!$student) {
                Log::warning('Student not found for deletion.', ['student_id' => $id]);
                return redirect()->route('students.index')->with('error', '学生が見つかりませんでした。');
            }

            // モデルのビジネスロジックを呼び出し
            $student->delete();

            Log::info('Student deleted successfully.', ['student_id' => $id]);
            return redirect()->route('students.index')->with('success', '学生が削除されました。');
        } catch (\Exception $e) {
            Log::error('Error occurred while deleting student.', ['error_message' => $e->getMessage()]);
            return redirect()->route('students.index')->with('error', '学生の削除中にエラーが発生しました。');
        }
    }
}