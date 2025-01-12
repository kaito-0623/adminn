<?php

namespace App\Http\Controllers;

use App\Student; // Student モデルをインポート
use App\SchoolGrade; // SchoolGrade モデルをインポート
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // ログをインポート
use App\Utils\CustomMimeTypes; // CustomMimeTypes クラスをインポート

class NewStudentController extends Controller
{
    // 学生一覧を表示するメソッド
    public function index(Request $request)
    {
        Log::info('Index method called.');
        // 名前および学年でフィルタリングして学生データを取得
        $students = Student::with('schoolGrades')
            ->filterByName($request->input('name'))
            ->filterByGrade($request->input('grade'))
            ->get();

        // 学生データのログを出力
        foreach ($students as $student) {
            Log::info('Student:', [
                'id' => $student->id,
                'name' => $student->name,
                'grade' => $student->grade,
                'schoolGrades' => $student->schoolGrades->toArray()
            ]);
        }

        // 学年データを取得
        $grades = Student::distinct()->pluck('grade');

        Log::info('Returning view with students and grades.');
        return view('students.index', [
            'students' => $students,
            'grades' => $grades,
        ]);
    }

    // 学生登録画面を表示するメソッド
    public function create()
    {
        Log::info('Create method called.');
        // 学年データを取得
        $grades = SchoolGrade::distinct()->pluck('grade');
        return view('students.create', compact('grades'));
    }

    // 学生情報を保存するメソッド
    public function store(Request $request)
    {
        Log::info('Store method called.');
        Log::info('Request data:', $request->all());

        try {
            Log::info('Starting validation.');
            // バリデーション
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:students',
                'grade' => 'required|string|max:255',
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            Log::info('Validation passed.', $validated);

            $student = new Student();
            Log::info('Filling student data.');
            // フォームデータを埋め込む（写真を除く）
            $student->fill($request->except('photo'));

            // `grade_id` を設定する（適切な処理が必要）
            $student->grade_id = $this->getGradeId($request->input('grade'));

            // 写真のアップロード処理
            if ($request->hasFile('photo')) {
                try {
                    $file = $request->file('photo');
                    Log::info('File details', [
                        'original_name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'extension' => $file->getClientOriginalExtension(),
                    ]);

                    $customMimeTypes = new CustomMimeTypes();
                    $extension = $file->getClientOriginalExtension();
                    $mimeType = $customMimeTypes->guessMimeType($extension);
                    Log::info('Guessed MIME Type: ' . $mimeType);
                    // 写真を保存する
                    $path = $file->store('photos', 'public');
                    Log::info('File stored at path: ' . $path);
                    $student->img_path = $path;
                } catch (\Exception $e) {
                    Log::error('File Upload Error: ' . $e->getMessage());
                    Log::error('Trace: ' . $e->getTraceAsString());
                    return redirect()->back()->withErrors(['photo' => 'ファイルアップロードに失敗しました。']);
                }
            }

            // 学生データを保存する
            $student->save();

            Log::info('Student data saved:', $student->toArray());
            Log::info('Store method completed.');

            return redirect()->route('students.index')->with('success', '学生情報が登録されました');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed: ' . $e->getMessage());
            Log::error('Validation errors: ' . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('An error occurred in the store method: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', '学生情報の登録に失敗しました。');
        }
    }

    // 学年に対応する ID を取得するメソッド
    private function getGradeId($grade)
    {
        // `grade` に対応する `grade_id` を取得する処理
        $gradeMapping = [
            '1年生' => 1,
            '2年生' => 2,
            '3年生' => 3,
            '4年生' => 4,
        ];

        return $gradeMapping[$grade] ?? null;
    }

    // 学生編集画面を表示するメソッド
    public function edit(Student $student)
    {
        Log::info('Edit method called.');
        // 学年データを取得
        $grades = SchoolGrade::distinct()->pluck('grade');
        return view('students.edit', compact('student', 'grades'));
    }

    // 学生情報を更新するメソッド
    public function update(Request $request, Student $student)
    {
        Log::info('Update method called.');
        // バリデーション
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students,email,' . $student->id,
            'grade' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        Log::info('Updating student data.');
        // フォームデータを埋め込む（写真を除く）
        $student->fill($request->except('photo'));

        // 写真の保存処理
        if ($request->hasFile('photo')) {
            $student->savePhoto($request->file('photo'));
        }

        // `grade_id` を設定する（適切な処理が必要）
        $student->grade_id = $this->getGradeId($request->input('grade'));

        // 学生データを保存する
        $student->save();

        Log::info('Student data updated:', $student->toArray());
        return redirect()->route('students.index')->with('success', '学生情報が更新されました');
    }

    // 学生情報を削除するメソッド
    public function destroy(Student $student)
    {
        Log::info('Destroy method called.');
        // 写真の削除処理
        $student->deletePhoto();
        // 学生データを削除する
        $student->delete();

        Log::info('Student data deleted.');
        return redirect()->route('students.index')->with('success', '学生情報が削除されました');
    }

    // 学生詳細画面を表示するメソッド
    public function show(Student $student)
    {
        Log::info('Show method called.');

        // 学生の成績データを取得
        $schoolGrades = $student->schoolGrades;

        Log::info('Student details:', $student->toArray());
        Log::info('Student grades:', $schoolGrades->toArray());

        return view('students.show', compact('student', 'schoolGrades'));
    }

    // 学生情報を検索するメソッド
    public function search(Request $request)
    {
        $name = $request->input('name');
        $grade = $request->input('grade');
        $query = Student::query();

        if (!empty($name)) {
            $query->where('name', 'LIKE', "%$name%");
        }

        if (!empty($grade)) {
            $query->where('grade', $grade);
        }

        $students = $query->get();

        return view('students.partials.students_table', compact('students'));
    }

    // 学年でソートするメソッド
    public function sort(Request $request)
    {
        $order = $request->input('order');
        $students = Student::orderBy('grade', $order)->get();

        return view('students.partials.students_table', compact('students'));
    }

    // 学生詳細画面で成績をフィルタリングするメソッド
public function filterStudentGrades(Request $request, Student $student)
{
    $grade = $request->input('grade');
    $term = $request->input('term');
    
    $query = $student->schoolGrades();

    if (!empty($grade)) {
        $query->where('grade', $grade);
    }

    if (!empty($term)) {
        $query->where('term', $term);
    }

    $grades = $query->get();

    return view('students.partials.grades_table', compact('grades'));
}

// 学年でソートするメソッド
public function sortStudentGrades(Request $request, Student $student)
{
    $order = $request->input('order');
    $grades = $student->schoolGrades()->orderBy('grade', $order)->get();

    return view('students.partials.grades_table', compact('grades'));
}




}
