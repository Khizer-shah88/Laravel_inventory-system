@extends('layouts.app')
@section('page_title', 'Edit Account')

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

<form action="{{ route('Accounts.update', $account->AccountId) }}" method="POST" class="row g-4">
    @csrf 
    @method('PUT')

    <div class="col-md-6">
        <!-- Header Code -->
        <div class="mb-3">
            <label>Header Code</label>
            <input type="text" id="headerCode" name="HeaderCode" class="form-control" 
                value="{{ old('HeaderCode', $account->HeaderCode) }}" readonly required>
        </div>

        <!-- Account Type dropdown -->
        <div class="mb-3">
            <label>Account Type</label>
            <select id="accountType" name="AccountType" class="form-select" required>
                <option value="">-- Select Account Type --</option>
                <option data-code="301" {{ old('AccountType', $account->AccountType) == 'Expense' ? 'selected' : '' }}>Expense</option>
                <option data-code="701" {{ old('AccountType', $account->AccountType) == 'Bank' ? 'selected' : '' }}>Bank</option>
                <option data-code="702" {{ old('AccountType', $account->AccountType) == 'Personal Loan' ? 'selected' : '' }}>Personal Loan</option>
                <option data-code="703" {{ old('AccountType', $account->AccountType) == 'Staff Loan' ? 'selected' : '' }}>Staff Loan</option>
                <option data-code="101" {{ old('AccountType', $account->AccountType) == 'Customer' ? 'selected' : '' }}>Customer</option>
                <option data-code="201" {{ old('AccountType', $account->AccountType) == 'Company' ? 'selected' : '' }}>Company</option>
                <option data-code="401" {{ old('AccountType', $account->AccountType) == 'Capital' ? 'selected' : '' }}>Capital</option>
                <option data-code="501" {{ old('AccountType', $account->AccountType) == 'Cash In Hand' ? 'selected' : '' }}>Cash In Hand</option>
                <option data-code="801" {{ old('AccountType', $account->AccountType) == 'Stock' ? 'selected' : '' }}>Stock</option>
                <option data-code="901" {{ old('AccountType', $account->AccountType) == 'Company Claim' ? 'selected' : '' }}>Company Claim</option>
            </select>
        </div>

        <!-- Account Name -->
        <div class="mb-3">
            <label>Account Name</label>
            <input type="text" name="AccountName" class="form-control" 
                value="{{ old('AccountName', $account->AccountName) }}" required>
        </div>

<!-- Phone, Town, DSF -->
<div id="customerFields" style="display: none;">
    <div class="mb-3">
        <label>DSF</label>
        <select name="DSF" class="form-select">
            <option value="">-- Select DSF --</option>
            @foreach($dsfName as $data)
                <option value="{{ $data->DSF }}" {{ old('DSF', $account->DSF) == $data->DSF ? 'selected' : '' }}>
                    {{ $data->DSF }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Phone</label>
        <input type="text" name="Phone" class="form-control" 
            value="{{ old('Phone', $account->Phone) }}">
    </div>
    <div class="mb-3">
        <label>Town</label>
        <input type="text" name="Town" class="form-control" 
            value="{{ old('Town', $account->Town) }}">
    </div>
</div>


    </div>

    <div class="col-12">
        <button class="btn btn-success">💾 Update</button>
        <a href="{{ route('Accounts.index') }}" class="btn btn-secondary">↩ Cancel</a>
    </div>
</form>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const accountTypeSelect = document.getElementById("accountType");
    const headerCodeInput = document.getElementById("headerCode");
    const customerFields = document.getElementById("customerFields");

    function toggleCustomerFields() {
        const code = headerCodeInput.value; // always read current header code
        if (code === "101" || code === "201") {
            customerFields.style.display = "block";
        } else {
            customerFields.style.display = "none";
        }
    }

    function setHeaderCode() {
        const selected = accountTypeSelect.options[accountTypeSelect.selectedIndex];
        const code = selected.getAttribute("data-code") || "";
        headerCodeInput.value = code;
        toggleCustomerFields();
    }

    // Run on page load (in case account is already Customer/Company)
    toggleCustomerFields();

    // Update on change
    accountTypeSelect.addEventListener("change", setHeaderCode);
});
</script>

@endsection
