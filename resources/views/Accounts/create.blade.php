@extends('layouts.app')
@section('page_title', 'Add Account')

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

<form id="accountForm" action="{{ route('Accounts.store') }}" method="POST" class="row g-4">
    @csrf
    <div class="col-md-6">
        <div class="mb-3">
            <label>Header Code</label>
            <input type="text" id="headerCode" name="HeaderCode" class="form-control" value="{{ old('HeaderCode') }}" required readonly>
        </div>

        <div class="mb-3">
            <label>Account Type</label>
            <select id="accountType" name="AccountType" class="form-control" required>
                <option value="">-- Select Account Type --</option>
                <option data-code="301" {{ old('AccountType') == 'Expense' ? 'selected' : '' }}>Expense</option>
                <option data-code="701" {{ old('AccountType') == 'Bank' ? 'selected' : '' }}>Bank</option>
                <option data-code="702" {{ old('AccountType') == 'Personal Loan' ? 'selected' : '' }}>Personal Loan</option>
                <option data-code="703" {{ old('AccountType') == 'Staff Loan' ? 'selected' : '' }}>Staff Loan</option>
                <option data-code="101" {{ old('AccountType') == 'Customer' ? 'selected' : '' }}>Customer</option>
                <option data-code="201" {{ old('AccountType') == 'Company' ? 'selected' : '' }}>Company</option>
                <option data-code="401" {{ old('AccountType') == 'Capital' ? 'selected' : '' }}>Capital</option>
                <option data-code="501" {{ old('AccountType') == 'Cash In Hand' ? 'selected' : '' }}>Cash In Hand</option>
                <option data-code="801" {{ old('AccountType') == 'Stock' ? 'selected' : '' }}>Stock</option>
                <option data-code="901" {{ old('AccountType') == 'Company Claim' ? 'selected' : '' }}>Company Claim</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Account Name</label>
            <input type="text" name="AccountName" class="form-control" value="{{ old('AccountName') }}" required>
        </div>

        {{-- Hidden by default, visible only when Customer is selected --}}
        <div id="customerFields" style="display: none;">
            <div class="mb-3">
                <label>Town</label>
                <input type="text" name="Town" class="form-control" value="{{ old('Town') }}">
            </div>

            <div class="mb-3">
                <label>Phone</label>
                <input type="text" name="Phone" class="form-control" value="{{ old('Phone') }}">
            </div>
        </div>
    </div>

    <div class="col-12">
        <button id="saveBtn" type="submit" class="btn btn-success">💾 Save</button>
        <a href="{{ route('Accounts.index') }}" class="btn btn-secondary">↩ Cancel</a>
    </div>
</form>

@push('scripts')
<script>
document.getElementById('accountType').addEventListener('change', function () {
    let selectedOption = this.options[this.selectedIndex];
    let headerCodeInput = document.getElementById('headerCode');
    let customerFields = document.getElementById('customerFields');

    headerCodeInput.value = selectedOption.getAttribute('data-code'); 

    // Show extra fields only if "Customer" is selected
    customerFields.style.display = (this.value === "Customer") ? "block" : "none";
});

// Run on page load (if old value is already "Customer")
window.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('accountType').value === "Customer") {
        document.getElementById('customerFields').style.display = "block";
    }
});

// 🚫 Disable Save button after submit
document.getElementById('accountForm').addEventListener('submit', function () {
    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.textContent = "Saving..."; // optional UX feedback
});
</script>
@endpush
@endsection
