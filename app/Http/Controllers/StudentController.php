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
        // ソート条件を取得（デフォルトは昇順）
        $sortOrder = $request->input('sortOrder', 'asc');

        // フィルタリング（名前や学年を利用）＋ ソート条件適用
        $students = Student::query()
            ->filterByName($request->input('name')) // 名前でフィルタリング
            ->filterByGrade($request->input('grade')) // 学年でフィルタリング
            ->orderBy('grade', $sortOrder) // 学年でソート
            ->get();

        // 学生一覧ビューを返す
        return view('students.index', compact('students', 'sortOrder'));
    }

    // 学生作成画面を表示するメソッド
    public function create()
    {
        return view('students.create'); // ビューを返す
    }


    //Model委譲とカプセル化OK
    public function store(StudentRequest $request)
{
    try {
        // `validated()` を使用してバリデーション済みデータを取得し、 `createStudent()` に渡す
        $student = Student::createStudent($request->validated(), $request->file('photo'));

        return redirect()->route('students.show', $student->id)->with('success', '学生情報が登録されました。');
    } catch (\Exception $e) {
        Log::error('Error creating student.', [
            'error_message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->route('students.index')->with('error', '学生情報の登録中にエラーが発生しました。');
    }
}


    /**
 * 学生情報と成績データを表示するメソッド
 */
//Model委譲とカプセル化OK
public function show($id)
{
    try {
        // 学生データと成績データをモデルから取得
        $data = Student::getStudentWithGrades($id);

        // データが取得できなかった場合はエラーメッセージを表示
        if (!$data) {
            return redirect()->route('students.index')->with('error', '学生詳細を取得できませんでした。');
        }

        // 取得したデータをビューに渡して表示
        return view('students.show', $data);
    } catch (\Exception $e) {
        // エラー発生時の処理
        return redirect()->route('students.index')->with('error', 'エラーが発生しました。');
    }
}

    public function update(StudentRequest $request, $id)
{
    try {
        // **モデルのメソッドに処理を委譲**
        $student = Student::findOrFail($id);
        $student->updateStudent($request->validated(), $request->file('photo'));

        return redirect()->route('students.show', $student->id)->with('success', '学生情報が更新されました。');
    } catch (\Exception $e) {
        Log::error('Error updating student.', [
            'student_id' => $id,
            'error_message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->route('students.index')->with('error', '学生情報の更新中にエラーが発生しました。');
    }
}

    //Model委譲とカプセル化OK
    // 学生情報を編集する画面を表示するメソッド
    public function edit($id) 
{
    try {
        // 学生データをモデルから取得
        $student = Student::getStudentById($id);

        if (!$student) {
            return redirect()->route('students.index')->with('error', '編集画面の表示中にエラーが発生しました。');
        }

        return view('students.edit', compact('student'));
    } catch (\Exception $e) {
        return redirect()->route('students.index')->with('error', 'エラーが発生しました。');
    }
}

    // 全ての学生の学年を一括更新するメソッド Model委譲とカプセルOK
    public function updateAllGrades()
{
    try {
        Student::updateAllGrades(); //モデルメソッドを統一
        return redirect()->route('students.index')->with('success', '全ての学生の学年が更新されました。');
    } catch (\Exception $e) {
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