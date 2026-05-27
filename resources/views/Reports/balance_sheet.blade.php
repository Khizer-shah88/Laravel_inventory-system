@extends('layouts.app')
@section('page_title', 'Balance Sheet')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Balance Sheet</h2>

    <div class="row">
        {{-- Left Column - Positive Accounts --}}
        <div class="col-md-6">
            <h4>Total Of Debit</h4>
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Header Code</th>
                        <th>Account Type</th>
                        <th class="text-end">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($positiveAccounts as $row)
                        <tr>
                            <td>{{ $row->HeaderCode }}</td>
                            <td>{{ $row->AccountType }}</td>
                            <td class="text-end">{{ number_format($row->Balance, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No Positive Accounts</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="2">Total Positive</td>
                        <td class="text-end">{{ number_format($totalPositive, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Right Column - Negative Accounts --}}
        <div class="col-md-6">
            <h4>Total Of Credit</h4>
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Header Code</th>
                        <th>Account Type</th>
                        <th class="text-end">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($negativeAccounts as $row)
                        <tr>
                            <td>{{ $row->HeaderCode }}</td>
                            <td>{{ $row->AccountType }}</td>
                            <td class="text-end">{{ number_format(abs($row->Balance), 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No Negative Accounts</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="2">Total Negative</td>
                        <td class="text-end">{{ number_format(abs($totalNegative), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Summary --}}
    <div class="mt-4 p-3 border rounded bg-light text-center">
        <h5 class="mb-3">Difference</h5>
        <p><strong>{{ number_format($totalPositive - abs($totalNegative), 2) }}</strong></p>
    </div>
</div>
@endsection
