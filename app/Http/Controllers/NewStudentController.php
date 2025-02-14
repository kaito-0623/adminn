<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Student;
use App\SchoolGrade;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class NewStudentController extends Controller
{
    public function index(Request $request)
    {
        Log::info('Index method called.');
        $students = Student::with('schoolGrades')
            ->filterByName($request->input('name'))
            ->filterByGrade($request->input('grade'))
            ->get();

        $grades = Student::distinct()->pluck('grade');

        Log::info('Returning view with students and grades.');
        return view('students.index', [
            'students' => $students,
            'grades' => $grades,
        ]);
    }

    public function createForm()
    {
        Log::info('Create method called.');
        $grades = SchoolGrade::distinct()->pluck('grade');
        return view('students.create', compact('grades'));
    }

    public function store(StudentRequest $request)
    {
        Log::info('Store method called.');

        try {
            $student = new Student();
            $student->saveStudent($request->validated(), $request->file('photo'));

            Log::info('Student data saved:', $student->toArray());
            return redirect()->route('students.index')->with('success', '学生情報が登録されました');
        } catch (\Exception $e) {
            Log::error('An error occurred in the store method: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', '学生情報の登録に失敗しました。');
        }
    }

    public function editForm(Student $student)
    {
        Log::info('Edit method called.');
        $grades = SchoolGrade::distinct()->pluck('grade');
        return view('students.edit', compact('student', 'grades'));
    }

    public function update(StudentRequest $request, Student $student)
    {
        Log::info('Update method called.');

        try {
            $student->saveStudent($request->validated(), $request->file('photo'));

            Log::info('Student data updated:', $student->toArray());
            return redirect()->route('students.index')->with('success', '学生情報が更新されました');
        } catch (\Exception $e) {
            Log::error('An error occurred in the update method: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', '学生情報の更新に失敗しました。');
        }
    }

    public function destroy(Student $student)
    {
        Log::info('Destroy method called.');
        $student->deletePhoto();
        $student->delete();

        Log::info('Student data deleted.');
        return redirect()->route('students.index')->with('success', '学生情報が削除されました');
    }

    public function show(Student $student)
    {
        Log::info('Show method called.');
        $schoolGrades = $student->schoolGrades;

        return view('students.show', compact('student', 'schoolGrades'));
    }

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

    public function sort(Request $request)
    {
        $order = $request->input('order');
        $students = Student::orderBy('grade', $order)->get();

        return view('students.partials.students_table', compact('students'));
    }

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

    public function sortStudentGrades(Request $request, Student $student)
    {
        $order = $request->input('order');
        $grades = $student->schoolGrades()->orderBy('grade', $order)->get();

        return view('students.partials.grades_table', compact('grades'));
    }
}
