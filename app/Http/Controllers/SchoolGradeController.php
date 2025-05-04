<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\SchoolGrade; // モデル
use App\Student; // 学生モデル

class SchoolGradeController extends Controller
{
    /** 
     * 成績一覧を表示するメソッド 
     */
    public function index(Request $request)
    {
        $studentId = $request->query('student_id'); // 学生IDでフィルタリング
        if (!$studentId) {
            Log::warning('Student ID not provided for grades index.');
            return redirect()->route('students.index')->with('error', '学生IDが指定されていません。');
        }

        $schoolGrades = SchoolGrade::where('student_id', $studentId)->paginate(10); // ページネーション
        Log::info('Grades retrieved for rendering:', [
            'student_id' => $studentId,
            'grades_count' => $schoolGrades->count()
        ]);

        return view('schoolGrades.index', compact('schoolGrades', 'studentId'));
    }

    /** 
     * 成績作成フォームを表示するメソッド 
     */
    public function create(Request $request)
    {
        $studentId = $request->query('student_id');
        if (!$studentId || !Student::find($studentId)) {
            Log::warning('Student ID is missing or invalid.');
            return redirect()->route('students.index')->with('error', '学生が見つかりません。');
        }

        $student = Student::findOrFail($studentId);
        Log::info('Create form accessed for student.', ['student_id' => $studentId]);
        return view('schoolGrades.create', compact('student'));
    }

    /** 
     * 成績を保存するメソッド 
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'grade' => 'required',
            'term' => 'required',
            'japanese' => 'required|numeric|min:0|max:100',
            'math' => 'required|numeric|min:0|max:100',
            'science' => 'required|numeric|min:0|max:100',
            'social_studies' => 'required|numeric|min:0|max:100',
            'music' => 'required|numeric|min:0|max:100',
            'home_economics' => 'required|numeric|min:0|max:100',
            'english' => 'required|numeric|min:0|max:100',
            'art' => 'required|numeric|min:0|max:100',
            'health_and_physical_education' => 'required|numeric|min:0|max:100',
        ]);

        try {
            SchoolGrade::create($request->all());
            Log::info('Grades successfully created.', ['student_id' => $request->student_id]);
            return redirect()->route('students.show', $request->student_id)->with('success', '成績が登録されました。');
        } catch (\Exception $e) {
            Log::error('Error storing grades.', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', '成績の登録に失敗しました。');
        }
    }

    /** 
     * 学生詳細画面で成績一覧を渡すメソッド 
     */
    public function show($id)
    {
        try {
            $student = Student::findOrFail($id);
            $grades = SchoolGrade::where('student_id', $id)->get();

            Log::info('Student and grades details retrieved.', ['student_id' => $id, 'grades_count' => $grades->count()]);

            return view('students.show', compact('student', 'grades'));
        } catch (\Exception $e) {
            Log::error('Error retrieving grades.', ['message' => $e->getMessage()]);
            return redirect()->route('students.index')->with('error', '学生詳細画面の取得に失敗しました。');
        }
    }

    /** 
     * 成績検索メソッド 
     */
    public function search(Request $request, $studentId)
    {
        try {
            $gradeMap = [
                '1年生' => 1,
                '2年生' => 2,
                '3年生' => 3,
                '4年生' => 4,
            ];

            $termMap = [
                '1学期' => '1学期',
                '2学期' => '2学期',
                '3学期' => '3学期',
            ];

            $grade = $gradeMap[$request->input('grade')] ?? null;
            $term = $termMap[$request->input('term')] ?? null;

            $grades = SchoolGrade::query()
                ->where('student_id', $studentId)
                ->when($grade, function ($query, $grade) {
                    return $query->where('grade', $grade);
                })
                ->when($term, function ($query, $term) {
                    return $query->where('term', $term);
                })
                ->orderByGrade($request->input('order', 'asc'))
                ->get();

            Log::info('Search executed successfully.', [
                'student_id' => $studentId,
                'grades_count' => $grades->count(),
            ]);

            $student = Student::findOrFail($studentId);
            return view('students.show', compact('grades', 'student'));
        } catch (\Exception $e) {
            Log::error('Error occurred during search.', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', '成績検索中にエラーが発生しました。');
        }
    }

    /** 
     * 成績編集フォームを表示するメソッド 
     */
    public function edit($id)
    {
        try {
            $schoolGrade = SchoolGrade::findOrFail($id);
            Log::info('Edit form accessed for grade.', ['grade_id' => $id]);
            return view('schoolGrades.edit', compact('schoolGrade'));
        } catch (\Exception $e) {
            Log::error('Error accessing edit form.', ['message' => $e->getMessage()]);
            return redirect()->route('schoolGrades.index')->with('error', '成績の編集画面にアクセスできませんでした。');
        }
    }

    /** 
     * 成績を更新するメソッド（修正済み） 
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'grade' => 'required',
            'term' => 'required',
            'japanese' => 'required|numeric|min:0|max:100',
            'math' => 'required|numeric|min:0|max:100',
            'science' => 'required|numeric|min:0|max:100',
            'social_studies' => 'required|numeric|min:0|max:100',
            'music' => 'required|numeric|min:0|max:100',
            'home_economics' => 'required|numeric|min:0|max:100',
            'english' => 'required|numeric|min:0|max:100',
            'art' => 'required|numeric|min:0|max:100',
            'health_and_physical_education' => 'required|numeric|min:0|max:100',
        ]);

        try {
            // 安全なデータを使用して成績を更新
            $updatedGrade = SchoolGrade::updateGrade($id, $validatedData);
            Log::info('Grade successfully updated.', ['grade_id' => $id]);
            return redirect()->route('students.show', $updatedGrade->student_id)->with('success', '成績が更新されました。');
        } catch (\Exception $e) {
            Log::error('Error updating grade.', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', '成績の更新に失敗しました。');
        }
    }

    /** 
     * 成績を削除するメソッド（修正済み） 
     */
    public function destroy($id)
{
    try {
        Log::info('Destroy method accessed for grade.', ['grade_id' => $id]);

        // `deleteGrade` メソッドを呼び出す
        $studentId = SchoolGrade::deleteGrade($id);

        Log::info('Grade deleted successfully.', ['grade_id' => $id]);

        // 削除後、学生詳細画面にリダイレクト
        return redirect()->route('students.show', $studentId)->with('success', '成績が削除されました。');
    } catch (\Exception $e) {
        Log::error('Error occurred while deleting grade.', ['error_message' => $e->getMessage()]);
        return redirect()->route('students.index')->with('error', '成績の削除中にエラーが発生しました。');
    }
}
}