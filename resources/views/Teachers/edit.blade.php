@extends('layouts.app')
@section('page_title', 'Edit Teacher')

@section('content')


<form action="{{ route('Teachers.update', $teacher->id) }}" method="POST" class="row g-4">
    @csrf 
    @method('PUT')

    {{-- Left Column --}}
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Teacher Name</label>
            <input type="text" name="TeacherName" class="form-control" value="{{ $teacher->TeacherName }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Father's Name</label>
            <input type="text" name="Father" class="form-control" value="{{ $teacher->Father }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Qualification</label>
            <input type="text" name="Qualification" class="form-control" value="{{ $teacher->Qualification }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Experience</label>
            <input type="text" name="Experience" class="form-control" value="{{ $teacher->Experience }}" required>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="col-md-6">


        <div class="mb-3">
            <label class="form-label">Last School</label>
            <input type="text" name="LastSchool" class="form-control" value="{{ $teacher->LastSchool }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Joining Date</label>
            <input type="date" name="JoiningDate" class="form-control" value="{{ $teacher->JoiningDate }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Salary</label>
            <input type="number" name="Salary" step="0.01" class="form-control" value="{{ $teacher->Salary }}" required>
        </div>
    </div>

    {{-- Full Width Buttons --}}
    <div class="col-12">
        <button class="btn btn-success">💾 Update</button>
        <a href="{{ route('Teachers.index') }}" class="btn btn-secondary">↩ Cancel</a>
    </div>
</form>
@endsection
