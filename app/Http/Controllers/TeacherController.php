<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = DB::table('tblTeachers')
            ->orderByDesc('id')
            ->paginate(10);

        return view('Teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('Teachers.create');
    }

public function store(Request $request)
{
    $request->validate([
        'TeacherName'   => 'required|string|max:255',
        'Father'        => 'nullable|string|max:255',
        'Qualification' => 'required|string|max:255',
        'Experience'    => 'required|string|max:255',
        'LastSchool'    => 'nullable|string|max:255',
        'JoiningDate'   => 'required|date',
        'Salary'        => 'required|numeric|min:0',
    ]);

    DB::table('tblTeachers')->insert([
        'TeacherName'   => $request->TeacherName,
        'Father'        => $request->Father,
        'Qualification' => $request->Qualification,
        'Experience'    => $request->Experience,
        'LastSchool'    => $request->LastSchool,
        'JoiningDate'   => $request->JoiningDate,
        'Salary'        => $request->Salary,
        'created_at'        => now(),
        'updated_at'        => now(),
    ]);

    return redirect()->route('Teachers.index')->with('success', 'Teacher added successfully.');
}


    public function edit($id)
    {
        $teacher = DB::table('tblTeachers')->where('id', $id)->first();
        if (!$teacher) {
            return redirect()->route('Teachers.index')->with('error', 'Teachers not found.');
        }
        return view('Teachers.edit', compact('teacher'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'TeacherName' => 'required',
        ]);

        DB::table('tblTeachers')->where('id', $id)->update([
            'TeacherName' => $request->TeacherName,
            'Qualification' => $request->Qualification,
            'Experience' => $request->Experience,
            'JoiningDate' => $request->JoiningDate,
            'Salary' => $request->Salary,
            'Father' => $request->Father,
            'LastSchool' => $request->LastSchool,
            'updated_at' => now(),
        ]);

        return redirect()->route('Teachers.index')->with('success', 'Teachers updated successfully.');
    }

    public function destroy($id)
    {
        DB::table('tblTeachers')->where('id', $id)->delete();
        return redirect()->route('Teachers.index')->with('success', 'Teacher deleted successfully.');
    }
}