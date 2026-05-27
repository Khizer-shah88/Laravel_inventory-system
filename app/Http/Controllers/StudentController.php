<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
public function index(Request $request)
{
    $search = $request->input('search');

    $students = DB::table('tblStudents')
        ->when($search, function($query, $search) {
            return $query->where('StudentName', 'like', "%{$search}%")
                         ->orWhere('RollNo', 'like', "%{$search}%");
        })
        ->orderBy('id', 'desc')
        ->paginate(10);

    return view('Students.index', compact('students'));
}


    public function create()
    {
        return view('Students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'StudentName' => 'required',
            'RollNo' => 'required|unique:tblStudents,RollNo',
            'Class' => 'required',
            'AdmissionDate' => 'required|date',
            'Fee' => 'required|numeric',
            'Father' => 'nullable|string|max:255',
            'Age' => 'nullable|integer|min:1|max:100',
            'LastSchool' => 'nullable|string|max:255',
        ]);

        DB::table('tblStudents')->insert([
            'StudentName' => $request->StudentName,
            'RollNo' => $request->RollNo,
            'Class' => $request->Class,
            'AdmissionDate' => $request->AdmissionDate,
            'Fee' => $request->Fee,
            'Father' => $request->Father,
            'Age' => $request->Age,
            'LastSchool' => $request->LastSchool,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('Students.index')->with('success', 'Student added successfully.');
    }

    public function edit($id)
    {
        $student = DB::table('tblStudents')->where('id', $id)->first();
        if (!$student) {
            return redirect()->route('students.index')->with('error', 'Student not found.');
        }
        return view('Students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'StudentName' => 'required',
            'RollNo' => 'required|unique:tblStudents,RollNo,' . $id,
            'Class' => 'required',
            'AdmissionDate' => 'required|date',
            'Fee' => 'required|numeric',
            'Father' => 'nullable|string|max:255',
            'Age' => 'nullable|integer|min:1|max:100',
            'LastSchool' => 'nullable|string|max:255',
        ]);

        DB::table('tblStudents')->where('id', $id)->update([
            'StudentName' => $request->StudentName,
            'RollNo' => $request->RollNo,
            'Class' => $request->Class,
            'AdmissionDate' => $request->AdmissionDate,
            'Fee' => $request->Fee,
            'Father' => $request->Father,
            'Age' => $request->Age,
            'LastSchool' => $request->LastSchool,
            'updated_at' => now(),
        ]);

        return redirect()->route('Students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy($id)
    {
        DB::table('tblStudents')->where('id', $id)->delete();
        return redirect()->route('Students.index')->with('success', 'Student deleted successfully.');
    }
}
