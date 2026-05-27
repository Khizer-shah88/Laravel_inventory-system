@extends('layouts.app')
@section('page_title', 'Students List')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('Students.create') }}" class="btn btn-primary">+ Add Student</a>

    {{-- Search Form --}}
    <form action="{{ route('Students.index') }}" method="GET" class="d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Search by Name or RollNo" value="{{ request('search') }}">
        <button class="btn btn-outline-secondary">Search</button>
    </form>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Student Name</th>
            <th>Father</th>
            <th>Roll No</th>
            <th>Class</th>
            <th>Age</th>
            <th>Last School</th>
            <th>Admission Date</th>
            <th>Fee</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @forelse($students as $student)
        <tr>
            <td>{{ $student->id }}</td>
            <td>{{ $student->StudentName }}</td>
            <td>{{ $student->Father }}</td>
            <td>{{ $student->RollNo }}</td>
            <td>{{ $student->Class }}</td>
            <td>{{ $student->Age }}</td>
            <td>{{ $student->LastSchool }}</td>
            <td>{{ $student->AdmissionDate }}</td>
            <td>{{ $student->Fee }}</td>
            <td>
                <a href="{{ route('Students.edit', $student->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('Students.destroy', $student->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this student?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="10">No Students Found</td></tr>
    @endforelse
    </tbody>
</table>

{{ $students->links() }}
@endsection
