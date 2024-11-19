<?php

namespace App\Http\Controllers;

use App\Grade;
use App\Student;
use Illuminate\Http\Request;

class GradesController extends Controller
{
    public function index()
    {
        $grades = Grade::all();
        return view('grades.index', compact('grades'));
    }

    public function create(Request $request)
    {
        $student_id = $request->student_id;
        return view('grades.create', compact('student_id'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject' => 'required|string|max:255',
            'score' => 'required|numeric|min:0|max:100',
        ]);

        Grade::create($validatedData);

        return redirect()->route('grades.index')->with('success', '成績が追加されました');
    }

    public function show($id)
    {
        $grade = Grade::findOrFail($id);
        return view('grades.show', compact('grade'));
    }

    public function edit($id)
    {
        $grade = Grade::findOrFail($id);
        return view('grades.edit', compact('grade'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'subject' => 'required|string|max:255',
            'score' => 'required|numeric|min:0|max:100',
        ]);

        $grade = Grade::findOrFail($id);
        $grade->update($validatedData);

        return redirect()->route('grades.index')->with('success', '成績が更新されました');
    }

    public function destroy($id)
    {
        $grade = Grade::findOrFail($id);
        $grade->delete();

        return redirect()->route('grades.index')->with('success', '成績が削除されました');
    }

    public function updateGrades() // 学年更新機能
    {
        // すべての学生の学年を1つ上げる
        Student::all()->each(function($student) {
            $student->grade += 1;
            $student->save();
        });

        return redirect()->route('menu.index')->with('success', '学年が更新されました');
    }
}

