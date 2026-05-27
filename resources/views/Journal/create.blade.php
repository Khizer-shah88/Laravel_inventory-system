@extends('layouts.app')
@section('page_title', 'Add Journal Entry')

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


<form action="{{ route('journal.store') }}" method="POST" onsubmit="return confirmSubmit();">
    @csrf
    <div class="row mb-3">
        <div class="col-md-3">
            <label>CB Date</label>
            <input type="date" name="CBDate" class="form-control" value="{{ now()->toDateString() }}" required>
        </div>
    </div>

    <!-- Journal Details Table -->
    <table class="table table-bordered table-sm" id="journalTable">
        <thead>
            <tr>
                <th style="width:10%;">Header Code</th>
                <th style="width:10%;">Account ID</th>
                <th>Account Name</th>
                <th>Description</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="number" name="HeaderCode[]" class="form-control headerCode form-control-sm" readonly></td>
                <td><input type="number" name="AccountId[]" class="form-control form-control-sm accountId" readonly></td>
                <td>
                    <input list="accountsList" name="AccountName[]" class="form-control form-control-sm accountName" required>
<datalist id="accountsList">
    @foreach($accounts as $account)
        <option 
            value="{{ $account->AccountName . ' ' . $account->AccountType }}" 
            data-id="{{ $account->AccountId }}" 
            data-header="{{ $account->HeaderCode }}">
            {{ $account->Town. '-' .$account->DSF}}
        </option>
    @endforeach
</datalist>

                </td>

                <td><input type="text" name="Description[]" class="form-control form-control-sm"></td>
                <td><input type="number" step="0.01" name="Debit[]" class="form-control form-control-sm debit-amount"></td>
                <td><input type="number" step="0.01" name="Credit[]" class="form-control form-control-sm credit-amount"></td>
                <td><button type="button" class="btn btn-danger btn-sm removeRow">Remove</button></td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="table-info">
                <td colspan="4" class="text-end fw-bold">Totals:</td>
                <td class="fw-bold" id="debit-total">0.00</td>
                <td class="fw-bold" id="credit-total">0.00</td>
                <td></td>
            </tr>
            <tr class="table-success">
                <td colspan="4" class="text-end fw-bold">Balance:</td>
                <td colspan="2" class="fw-bold" id="balance-amount">0.00</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="mb-3">
        <button type="button" id="addRow" class="btn btn-secondary">Add Row</button>
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-success">Save Journal</button>
        <a href="{{ route('journal.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Calculate and update totals
    function calculateTotals() {
        let debitTotal = 0;
        let creditTotal = 0;
        
        // Sum all debit values
        document.querySelectorAll('.debit-amount').forEach(input => {
            debitTotal += parseFloat(input.value) || 0;
        });
        
        // Sum all credit values
        document.querySelectorAll('.credit-amount').forEach(input => {
            creditTotal += parseFloat(input.value) || 0;
        });
        
        // Update the totals display
        document.getElementById('debit-total').textContent = debitTotal.toFixed(2);
        document.getElementById('credit-total').textContent = creditTotal.toFixed(2);
        
        // Calculate and display balance
        const balance = debitTotal - creditTotal;
        const balanceElement = document.getElementById('balance-amount');
        balanceElement.textContent = Math.abs(balance).toFixed(2);
        
        // Color code based on balance
        if (balance === 0) {
            balanceElement.parentElement.className = 'table-success';
            balanceElement.innerHTML = 'Balanced ✓';
        } else if (balance > 0) {
            balanceElement.parentElement.className = 'table-danger';
            balanceElement.innerHTML = 'Debit by ' + Math.abs(balance).toFixed(2);
        } else {
            balanceElement.parentElement.className = 'table-danger';
            balanceElement.innerHTML = 'Credit by ' + Math.abs(balance).toFixed(2);
        }
    }
    
    // Auto-fill HeaderCode and AccountId in a row
    function bindAutoFill(row) {
        const input = row.querySelector('.accountName');
        input.addEventListener('input', function() {
            const val = this.value;
            const option = Array.from(this.list.options).find(o => o.value === val);
            if(option){
                row.querySelector('.accountId').value = option.dataset.id;
                row.querySelector('.headerCode').value = option.dataset.header;
            } else {
                row.querySelector('.accountId').value = '';
                row.querySelector('.headerCode').value = '';
            }
        });
        
        // Add event listeners for debit/credit inputs to update totals
        const debitInput = row.querySelector('.debit-amount');
        const creditInput = row.querySelector('.credit-amount');
        
        debitInput.addEventListener('input', calculateTotals);
        creditInput.addEventListener('input', calculateTotals);
    }

    // Bind first row
    bindAutoFill(document.querySelector('#journalTable tbody tr'));

    // Add new row
    document.getElementById('addRow').addEventListener('click', function() {
        const tableBody = document.querySelector('#journalTable tbody');
        const newRow = tableBody.rows[0].cloneNode(true);

        // Clear values
        newRow.querySelectorAll('input').forEach(input => {
            if(input.type !== 'number') input.value = '';
            else input.value = '0';
        });

        tableBody.appendChild(newRow);
        bindAutoFill(newRow);
        
        // Recalculate totals after adding new row
        calculateTotals();

        // Focus on AccountName of the newly added row
        const lastRowAccountName = newRow.querySelector('.accountName');
        if(lastRowAccountName){
            lastRowAccountName.focus();
        }
    });

    // Remove row
    document.querySelector('#journalTable').addEventListener('click', function(e) {
        if(e.target && e.target.classList.contains('removeRow')){
            const rowCount = this.querySelectorAll('tbody tr').length;
            if(rowCount > 1){
                e.target.closest('tr').remove();
                calculateTotals(); // Recalculate after removal
            } else {
                alert('At least one row is required.');
            }
        }
    });
    
    // Add input event listeners to existing debit/credit fields
    document.querySelectorAll('.debit-amount, .credit-amount').forEach(input => {
        input.addEventListener('input', calculateTotals);
    });
    
    // Initial calculation
    calculateTotals();
});

function confirmSubmit() {
    // Check if the journal is balanced before submitting
    const balanceElement = document.getElementById('balance-amount');
    const isBalanced = !balanceElement.parentElement.classList.contains('table-danger');
    
    if (!isBalanced) {
        alert('Journal is not balanced. Please ensure debit and credit totals are equal before submitting.');
        return false;
    }
    
    return confirm('Are you sure you want to submit this journal entry?');
}
</script>
@endpush

@endsection