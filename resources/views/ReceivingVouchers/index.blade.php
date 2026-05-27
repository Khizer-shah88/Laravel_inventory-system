@extends('layouts.app')
@section('page_title', 'Receiving Vouchers')

@section('content')
<div class="d-flex justify-content-between mb-3">

    <a href="{{ route('ReceivingVouchers.create') }}" class="btn btn-primary">+ Add Voucher</a>

    {{-- Search Form --}}
    <form action="{{ route('ReceivingVouchers.index') }}" method="GET" class="d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Search by Account Name or ID" value="{{ request('search') }}">
        <button class="btn btn-outline-secondary">Search</button>
    </form>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Header Code</th>
            <th>Account ID</th>
            <th>Account Name</th>
            <th>Description</th>
            <th>Voucher Date</th>
            <th>Amount</th>
            <th width="150">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($vouchers as $voucher)
        <tr>
            <td>{{ $voucher->id }}</td>
            <td>{{ $voucher->HeaderCode }}</td>
            <td>{{ $voucher->AccountId }}</td>
            <td>{{ $voucher->AccountName }}</td>
            <td>{{ $voucher->Description }}</td>
            <td>{{ $voucher->VDate }}</td>
            <td>{{ number_format($voucher->Amount, 2) }}</td>
            <td>
                <a href="{{ route('ReceivingVouchers.edit', $voucher->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('ReceivingVouchers.destroy', $voucher->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Del</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="8">No Vouchers Found</td></tr>
        @endforelse
    </tbody>
</table>


@endsection
