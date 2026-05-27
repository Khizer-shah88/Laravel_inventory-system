@extends('layouts.app')
@section('page_title', 'Remaining Fees List')

@section('content')

    <table class="table table-bordered table-striped">
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
            @foreach($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->StudentName }}</td>
                    <td>{{ $student->AdmissionDate }}</td>
                    <td>{{ number_format($student->Fee, 2) }}</td>
                    <td>{{ number_format($student->PaidAmount, 2) }}</td>
                    <td class="text-danger fw-bold">{{ number_format($student->RemainingFee, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
