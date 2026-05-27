@extends('layouts.app')
@section('page_title', 'Edit Payment Voucher')

@section('content')
<h4>Edit Receiving Voucher</h4>

{{-- Show validation errors --}}
@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('PaymentVouchers.update', $voucher->id) }}" method="POST" class="row g-4">
    @csrf
    @method('PUT')

    {{-- Left Column --}}
    <div class="col-md-6">
        <div class="mb-3">
            <label>Header Code</label>
            <input type="text" name="HeaderCode" class="form-control" 
                value="{{ old('HeaderCode', $voucher->HeaderCode) }}" required>
        </div>
        <div class="mb-3">
            <label>Account ID</label>
            <input type="text" name="AccountId" class="form-control" 
                value="{{ old('AccountId', $voucher->AccountId) }}" required>
        </div>
        <div class="mb-3">
            <label>Account Name</label>
            <input type="text" name="AccountName" class="form-control" 
                value="{{ old('AccountName', $voucher->AccountName) }}" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="Description" class="form-control">{{ old('Description', $voucher->Description) }}</textarea>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="col-md-6">
        <div class="mb-3">
            <label>Voucher Date</label>
            <input type="date" name="VDate" class="form-control" 
                value="{{ old('VDate', $voucher->VDate) }}" required>
        </div>
        <div class="mb-3">
            <label>Amount</label>
            <input type="number" name="Amount" step="0.01" class="form-control" 
                value="{{ old('Amount', $voucher->Amount) }}" required>
        </div>
    </div>

    {{-- Full Width Buttons --}}
    <div class="col-12">
        <button class="btn btn-success">💾 Update</button>
        <a href="{{ route('PaymentVouchers.index') }}" class="btn btn-secondary">↩ Cancel</a>
    </div>
</form>
@endsection
