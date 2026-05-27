@extends('layouts.app')
@section('page_title', 'Add Receiving Voucher')

@section('content')
@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('ReceivingVouchers.store') }}" method="POST" class="row g-4">
    @csrf

<div class="row mt-3">
    <div class="col-md-6">
        <div class="mb-3">
            <label>Header Code</label>
            <input type="text" id="headerCode" name="HeaderCode" class="form-control" value="{{ old('HeaderCode') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label>Account ID</label>
            <input type="text" id="accountId" name="AccountId" class="form-control" value="{{ old('AccountId') }}" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label>Account Name</label>
            <input list="accounts" name="AccountName" id="accountName" class="form-control" autocomplete="off">
            <datalist id="accounts">
                @foreach($accounts as $account)
                    <option 
                        data-account="{{ $account->AccountId }}" 
                        data-header="{{ $account->HeaderCode }}" 
                        value="{{ $account->AccountName }}">
                    </option>
                @endforeach
            </datalist>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label>Description</label>
            <textarea name="Description" class="form-control">{{ old('Description') }}</textarea>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label>Voucher Date</label>
            <input type="date" name="VDate" class="form-control" value="{{ old('VDate', date('Y-m-d')) }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label>Amount</label>
            <input type="number" name="Amount" step="0.01" class="form-control" value="{{ old('Amount') }}" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <button class="btn btn-success">💾 Save</button>
        <a href="{{ route('ReceivingVouchers.index') }}" class="btn btn-secondary">↩ Cancel</a>
    </div>
</div>

</form>

@push('scripts')
<script>
const accountInput = document.getElementById('accountName');
const accountIdInput = document.getElementById('accountId');
const headerCodeInput = document.getElementById('headerCode');
const datalist = document.getElementById('accounts');

accountInput.addEventListener('input', function() {
    const val = this.value;
    const option = Array.from(datalist.options).find(o => o.value === val);

    if(option){
        accountIdInput.value = option.dataset.account;   // <-- must match data-account
        headerCodeInput.value = option.dataset.header;  // <-- must match data-header
        console.log("AccountId:", accountIdInput.value, "HeaderCode:", headerCodeInput.value);
    } else {
        accountIdInput.value = '';
        headerCodeInput.value = '';
    }
});
</script>
@endpush
@endsection
