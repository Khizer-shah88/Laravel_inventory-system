@extends('layouts.app')
@section('page_title', 'Add Student')

@section('content')
<h4 class="mb-4">Add Student</h4>

<form action="{{ route('Students.store') }}" method="POST" class="row g-4">
    @csrf

    {{-- Left Column --}}
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Student Name</label>
            <input type="text" name="StudentName" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Father's Name</label>
            <input type="text" name="Father" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Roll No</label>
            <input type="text" name="RollNo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Class</label>
            <input type="text" name="Class" class="form-control" required>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Age</label>
            <input type="number" name="Age" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Last School</label>
            <input type="text" name="LastSchool" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Admission Date</label>
            <input type="date" name="AdmissionDate" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fee</label>
            <input type="number" name="Fee" step="0.01" class="form-control" required>
        </div>
    </div>

    {{-- Full Width Buttons --}}
    <div class="col-12">
        <button class="btn btn-success">💾 Save</button>
        <a href="{{ route('Students.index') }}" class="btn btn-secondary">↩ Cancel</a>
    </div>
</form>
@endsection
