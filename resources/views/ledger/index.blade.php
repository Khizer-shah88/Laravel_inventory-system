@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Ledger Report Criteria</h2>

    <form action="{{ route('ledger.show') }}" method="POST" target="_blank">
        @csrf
        <div class="row ">
            <div class="col-md-6">
                <label>Header Code</label>
                <input type="text" id="headerCode" name="HeaderCode" class="form-control mb-3" required>

                <label>Account ID</label>
                <input type="text" id="accountId" name="AccountId" class="form-control mb-3" required>

                <label>Account Name</label>
                <input list="accounts" id="accountName" name="AccountName" class="form-control mb-3" autocomplete="off">
                <datalist id="accounts">
                    @foreach($accounts as $account)
                        <option data-account="{{ $account->AccountId }}" 
                                data-header="{{ $account->HeaderCode }}" 
                                value="{{ $account->AccountName }}">
                        </option>
                    @endforeach
                </datalist>

                <label>Start Date</label>
                <input type="date" name="StartDate" value="{{ now()->toDateString() }}" class="form-control mb-3" required>

                <label>End Date</label>
                <input type="date" name="EndDate" value="{{ now()->toDateString() }}" class="form-control mb-3" required>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary w-100">Show Report</button>
                </div>
            </div>
        </div>
    </form>
</div>

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
        accountIdInput.value = option.dataset.account;
        headerCodeInput.value = option.dataset.header;
    } else {
        accountIdInput.value = '';
        headerCodeInput.value = '';
    }
});
</script>
@endpush
@endsection
