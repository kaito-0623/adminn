<?php
namespace App\Http\Controllers;

use App\Student; // 修正: Student モデルをインポート
use App\Grade; // 修正: Grade モデルをインポート
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // ログをインポート


class NewStudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::query()
            ->filterByName($request->input('name'))
            ->filterByGrade($request->input('grade'))
            ->get();
        $grades = Grade::all();

        return view('students.index', [
            'students' => $students,
            'grades' => $grades,
        ]);
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'grade' => 'required|integer|min:1|max:12',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 画像のバリデーションを有効にする
        ]);

        $student = new Student();

        // 画像のアップロード処理
        if ($request->hasFile('photo')) {
            try {
                $file = $request->file('photo');
                $mimeType = $file->getMimeType();
                Log::info('MIME Type: ' . $mimeType);  // MIMEタイプをログに記録
                $path = $file->store('photos', 'public');
                Log::info('File path: ' . $path);  // ファイルパスをログに記録
                $student->img_path = $path;
            } catch (\Exception $e) {
                Log::error('File Upload Error: ' . $e->getMessage());  // エラーメッセージをログに記録
                Log::error('Trace: ' . $e->getTraceAsString()); // スタックトレースをログに記録
                return redirect()->back()->withErrors(['photo' => 'ファイルアップロードに失敗しました。']);
            }
        }

        $student->fill($request->except('photo'));
        $student->save();

        return redirect()->route('students.index')->with('success', '学生情報が登録されました');
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students,email,' . $student->id,
            'grade' => 'required|integer|min:1|max:12',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 画像のバリデーションを追加
        ]);

        // 学生データの更新
        $student->fill($request->except('photo'));

        // 画像のアップロード処理
        if ($request->hasFile('photo')) {
            $student->savePhoto($request->file('photo'));
        }

        $student->save();

        return redirect()->route('students.index')->with('success', '学生情報が更新されました');
    }

    public function destroy(Student $student)
    {
        // 画像の削除処理
        $student->deletePhoto();
        $student->delete();

        return redirect()->route('students.index');
    }

    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }
}
