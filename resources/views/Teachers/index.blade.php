@extends('layouts.app')
@section('page_title', 'Teachers List')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('Teachers.create') }}" class="btn btn-primary">+ Add Student</a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Teacher Name</th>
            <th>Father</th>
            <th>Qualification</th>
            <th>Experience</th>
            <th>Last School</th>
            <th>Joining Date</th>
            <th>Salary</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @forelse($teachers as $teacher)
        <tr>
            <td>{{ $teacher->id }}</td>
            <td>{{ $teacher->TeacherName }}</td>
            <td>{{ $teacher->Father }}</td>
            <td>{{ $teacher->Qualification }}</td>
            <td>{{ $teacher->Experience }}</td>
            <td>{{ $teacher->LastSchool }}</td>
            <td>{{ $teacher->JoiningDate }}</td>
            <td>{{ $teacher->Salary }}</td>
            <td>
                <a href="{{ route('Teachers.edit', $teacher->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('Teachers.destroy', $teacher->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this teacher?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="10">No Teachers Found</td></tr>
    @endforelse
    </tbody>
</table>

{{ $teachers->links() }}
@endsection
