@extends('layouts.app')
@section('page_title', 'Remaining Fees')

@section('content')


<table class="table table-bordered table-striped mt-3">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Student Name</th>
            <th>Admission Date</th>
            <th>Monthly Fee</th>
            <th>Paid Amount</th>
            <th>Remaining Fee</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($studentsRemaining as $index => $student)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $student->StudentName }}</td>
            <td>{{ \Carbon\Carbon::parse($student->AdmissionDate)->format('d-m-Y') }}</td>
            <td>{{ number_format($student->Fee, 2) }}</td>
            <td>{{ number_format($student->PaidAmount, 2) }}</td>
            <td>{{ number_format($student->RemainingFee, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
