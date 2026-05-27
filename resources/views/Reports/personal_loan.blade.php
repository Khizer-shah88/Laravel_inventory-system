@extends('layouts.app')

@section('content')
@section('page_title', 'Personal Loan')
<div class="container">
    

    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>Account Name</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
                <tr>
                    <td>{{ $row->AccountName }}</td>
                    <td>{{ number_format($row->Balance, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center">No records found</td>
                </tr>
            @endforelse
        </tbody>
            <tfoot>
        <tr>
            <th>Grand Total</th>
            <th>{{ number_format($grandTotal, 2) }}</th>
        </tr>
    </tfoot>
    </table>
</div>
@endsection