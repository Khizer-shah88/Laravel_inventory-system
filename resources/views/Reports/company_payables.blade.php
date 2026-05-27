@extends('layouts.app')

@section('content')
@section('page_title', 'Companies Payables')
<div class="container">
    

    <table class="table table-bordered table-sm table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>Account Name</th>
                <th>Town</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
                <tr>
                    <td>{{ $row->AccountName }}</td>
                    <td>{{ $row->Town }}</td>
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
            <th colspan="2">Grand Total</th>
            <th>{{ number_format($grandTotal, 2) }}</th>
        </tr>
    </tfoot>
    </table>
</div>
@endsection
