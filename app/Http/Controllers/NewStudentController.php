<?php

namespace App\Http\Controllers;

use App\Student;
use App\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Log ファサードのインポート

class NewStudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::query();
        $grades = Grade::all();

        if ($request->has('name') && $request->input('name') != '') {
            $students->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('grade') && $request->input('grade') != '') {
            $students->where('grade', $request->input('grade'));
        }

        return view('students.index', [
            'students' => $students->get(),
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
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 画像のバリデーションを追加
        ]);
    
        $student = new Student();
        $student->name = $request->name;
        $student->address = $request->address;
        $student->email = $request->email;
        $student->grade = $request->grade;
    
        // ファイルアップロード処理
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
    
        $student->save();
    
        return redirect()->route('students.index')->with('success', '学生情報が登録されました');
    }
    

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
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

        $student->name = $request->name;
        $student->address = $request->address;
        $student->email = $request->email;
        $student->grade = $request->grade;

        // ファイルアップロード処理
        if ($request->hasFile('photo')) {
            // 既存の画像を削除
            if ($student->img_path) {
                Storage::disk('public')->delete($student->img_path);
            }
            $path = $request->file('photo')->store('photos', 'public');
            $student->img_path = $path;
        }

        $student->save();

        return redirect()->route('students.index')->with('success', '学生情報が更新されました');
    }

    public function destroy(Student $student)
    {
        // 画像の削除
        if ($student->img_path) {
            Storage::disk('public')->delete($student->img_path);
        }
        $student->delete();
        return redirect()->route('students.index');
    }

    // 詳細表示用メソッドの追加
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

}
