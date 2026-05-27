@extends('layouts.app')
@section('page_title', 'Add Teacher')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<form action="{{ route('Teachers.store') }}" method="POST" class="row g-4">
    @csrf

    {{-- Left Column --}}
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Teacher Name</label>
            <input type="text" name="TeacherName" class="form-control" value="{{ old('TeacherName') }}">
            @error('StudentName')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Father's Name</label>
            <input type="text" name="Father" class="form-control" value="{{ old('Father') }}">
            @error('Father')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Qualification</label>
            <input type="text" name="Qualification" class="form-control" value="{{ old('Qualification') }}">
            @error('Qualification')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Experience</label>
            <input type="text" name="Experience" class="form-control" value="{{ old('Experience') }}">
            @error('Experience')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Right Column --}}
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Last School</label>
            <input type="text" name="LastSchool" class="form-control" value="{{ old('LastSchool') }}">
            @error('LastSchool')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Joining Date</label>
            <input type="date" name="JoiningDate" class="form-control" value="{{ old('JoiningDate') }}">
            @error('JoiningDate')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Salary</label>
            <input type="number" name="Salary" step="0.01" class="form-control" value="{{ old('Salary') }}">
            @error('Salary')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Buttons --}}
    <div class="col-12">
        <button class="btn btn-success">💾 Save</button>
        <a href="{{ route('Teachers.index') }}" class="btn btn-secondary">↩ Cancel</a>
    </div>
</form>



@endsection
