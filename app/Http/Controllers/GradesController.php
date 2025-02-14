<?php

namespace App\Http\Controllers;

use App\Http\Requests\GradeRequest;
use App\Grade;
use App\Student;
use Illuminate\Http\Request; // Request クラスのインポート
use Illuminate\Support\Facades\Log;

class GradesController extends Controller
{
    public function index()
    {
        $grades = Grade::all();
        return view('grades.index', compact('grades'));
    }

    public function createForm(Request $request)
    {
        $student_id = $request->query('student_id');
        Log::info('Create method called. Student ID:', ['student_id' => $student_id]);
        return view('grades.create', compact('student_id'));
    }

    public function store(GradeRequest $request)
    {
        Log::info('Store method called.');

        try {
            Grade::saveOrUpdate($request->validated());
            Log::info('Grade data saved.');
            return redirect()->route('students.show', $request->student_id)->with('success', '成績が登録されました');
        } catch (\Exception $e) {
            Log::error('An error occurred in the store method: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', '成績の登録に失敗しました。');
        }
    }

    public function show($id)
    {
        Log::info('Show method called.');
        
        $grade = Grade::findOrFail($id);
        return view('grades.show', compact('grade'));
    }

    public function editForm($id)
    {
        Log::info('Edit method called.');
        
        $grade = Grade::findOrFail($id);
        return view('grades.edit', compact('grade'));
    }

    public function update(GradeRequest $request, $id)
    {
        Log::info('Update method called.');

        try {
            $grade = Grade::findOrFail($id);
            $grade->update($request->validated());

            Log::info('Grade data updated:', $grade->toArray());
            return redirect()->route('grades.index')->with('success', '成績が更新されました');
        } catch (\Exception $e) {
            Log::error('An error occurred in the update method: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', '成績の更新に失敗しました。');
        }
    }

    public function destroy($id)
    {
        Log::info('Destroy method called.');

        try {
            $grade = Grade::findOrFail($id);
            $grade->delete();

            Log::info('Grade data deleted:', ['id' => $id]);
            return redirect()->route('grades.index')->with('success', '成績が削除されました');
        } catch (\Exception $e) {
            Log::error('An error occurred in the destroy method: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', '成績の削除に失敗しました。');
        }
    }

    public function updateStudentGrades()
    {
        Log::info('Update grades method called.');

        try {
            Student::all()->each(function($student) {
                $student->grade += 1;
                $student->save();

                Log::info('Student grade updated:', ['student_id' => $student->id, 'new_grade' => $student->grade]);
            });

            return redirect()->route('menu.index')->with('success', '学年が更新されました');
        } catch (\Exception $e) {
            Log::error('An error occurred in the updateGrades method: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', '学年の更新に失敗しました。');
        }
    }
}
