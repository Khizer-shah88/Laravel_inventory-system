@extends('layouts.app')

@section('page_title', 'Edit Purchase Invoice')

@section('content')

<div class="container">
    <!-- Success message -->
    @if(session('success'))
    <div id="successMsg" class="alert alert-success position-fixed top-0 end-0 m-3" style="z-index:9999;">
        {{ session('success') }}
    </div>
    @endif

    <!-- Validation Errors -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form id="saleInvoiceForm" action="{{ route('PurchaseInvoice.update', $saleInvoice->InvNo) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Master Section -->
        <div class="card mb-3">
            <div class="card-header bg-danger text-white">Invoice Details</div>
            <div class="card-body row g-2">
                <div class="col-md-4">
                    <label class="form-label">Invoice No</label>
                    <input type="text" name="InvoiceNo" class="form-control" value="{{ $saleInvoice->InvNo }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Invoice Date</label>
                    <input type="date" name="InvDate" class="form-control" value="{{ $saleInvoice->InvDate }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Header Code</label>
                    <input type="text" name="HeaderCode" id="HeaderCode" class="form-control" value="{{ $saleInvoice->HeaderCode }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Account ID</label>
                    <input type="number" name="AccountId" id="AccountId" class="form-control" value="{{ $saleInvoice->AccountId }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Account Name</label>
                    <input list="accountsList" id="AccountName" name="AccountName" class="form-control" value="{{ $saleInvoice->AccountName ?? 'Walking Customer' }}" required>
                    <datalist id="accountsList">
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->AccountName }}" data-headercode="{{ $acc->HeaderCode }}" data-accountid="{{ $acc->AccountId }}"></option>
                        @endforeach
                    </datalist>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Town</label>
                    <input type="text" name="Town" class="form-control" value="{{ $saleInvoice->Town }}">
                </div>
            </div>
        </div>

        <!-- Item Inputs -->
        <div class="row mb-3 g-2">
            <div class="col-md-6">
                <label class="form-label">Item Code</label>
                <input type="text" id="itemCodeInput" class="form-control" placeholder="Enter Item Code and press Enter">
            </div>
            <div class="col-md-6">
                <label class="form-label">Item Name</label>
                <input list="itemsList" id="itemNameInput" class="form-control" placeholder="Type Item Name">
                <datalist id="itemsList">
                    @foreach($allItems as $item)
                        <option value="{{ $item->ItemName }}" data-itemcode="{{ $item->ItemCode }}" data-saleprice="{{ $item->SalePrice }}"></option>
                    @endforeach
                </datalist>
            </div>
        </div>

<!-- Child Section -->
<div class="card mb-3">
    <div class="card-header bg-danger text-white">Invoice Items</div>
    <div class="card-body">
        <table class="table table-bordered" id="itemsTable">
            <thead class="table-light">
                <tr>
                    <th style="width:10%">Item Code</th>
                    <th style="width:25%">Item Name</th>
                    <th style="width:8%">Qty</th>
                    <th style="width:13%">Rate</th>
                    <th style="width:07%">Disc%</th>
                    <th style="width:10%">Disc Amt</th>
                    <th>Total</th>
                    <th style="width:5%">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                <tr id="row-{{ $index }}">
                    <td><input type="text" name="items[{{ $index }}][ItemCode]" class="form-control form-control-sm" value="{{ $item->ItemCode }}" readonly></td>
                    <td><input type="text" name="items[{{ $index }}][ItemName]" class="form-control form-control-sm" value="{{ $item->ItemName }}" readonly></td>
                    <td><input type="number" step="1" name="items[{{ $index }}][Qty]" class="form-control form-control-sm qty" value="{{ $item->Qty }}"></td>
                    <td><input type="number" step="0.01" name="items[{{ $index }}][Rate]" class="form-control form-control-sm rate" value="{{ $item->Rate }}"></td>
                    <td><input type="number" step="0.01" name="items[{{ $index }}][DiscPer]" class="form-control form-control-sm discper" value="{{ $item->DiscPer }}"></td>
                    <td><input type="number" step="0.01" name="items[{{ $index }}][Disc]" class="form-control form-control-sm disc" value="{{ $item->Disc }}" readonly></td>
                    <td><input type="number" step="0.01" class="form-control form-control-sm total" value="{{ ($item->Qty * $item->Rate) - $item->Disc }}" readonly></td>
                    <td><button type="button" class="btn btn-danger btn-delete btn-sm">×</button></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-info">
                    <td colspan="6" class="text-end fw-bold">Grand Total:</td>
                    <td><input type="number" step="0.01" id="grandTotal" class="form-control form-control-sm fw-bold" readonly value="0"></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="6" class="text-end">Cash Discount:</td>
                    <td><input type="number" step="0.01" id="cashDiscount" name="CashDiscount" class="form-control form-control-sm" value="{{ $saleInvoice->CashDiscount ?? 0 }}"></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="6" class="text-end">Cash Received:</td>
                    <td><input type="number" step="0.01" id="cashReceived" name="CashReceived" class="form-control form-control-sm" value="{{ $saleInvoice->CashReceived ?? 0 }}"></td>
                    <td></td>
                </tr>
                <tr class="table-primary">
                    <td colspan="6" class="text-end fw-bold">Net Amount:</td>
                    <td><input type="number" step="0.01" id="netAmount" class="form-control form-control-sm fw-bold" readonly value="0"></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

        <button type="submit" class="btn btn-primary">Update Invoice</button>
        <a href="{{ route('PurchaseInvoice.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function(){
    // Fade out success message
    setTimeout(function(){
        $('#successMsg').fadeOut('slow');
    }, 3000);

    // Set focus on ItemCode input
    $('#itemCodeInput').focus();

    let rowCount = {{ count($items) }};

    // Fill AccountId & HeaderCode when AccountName changes
    $('#AccountName').on('change', function(){
        let selected = $('#accountsList option').filter(function(){
            return $(this).val() === $('#AccountName').val();
        });
        $('#HeaderCode').val(selected.data('headercode') || '100');
        $('#AccountId').val(selected.data('accountid') || '8');
    });

    // Add row from ItemCode on Enter key
    $('#itemCodeInput').on('keypress', function(e){
        if(e.which === 13){
            e.preventDefault();
            let code = $(this).val();
            if(!code) return;

            $.ajax({
                url: '/items/' + code + '/details',
                method: 'GET',
                success: function(data){
                    if(data.ItemCode){
                        addItemRow({ItemCode: data.ItemCode, ItemName: data.ItemName, SalePrice: data.SalePrice});
                        $('#itemCodeInput').val('').focus();
                    } else { 
                        alert('Item not found'); 
                    }
                },
                error: function(){ alert('Error fetching item'); }
            });
        }
    });

    // Add row from ItemName selection
    $('#itemNameInput').on('change', function(){
        let selected = $('#itemsList option').filter(function(){
            return $(this).val() === $('#itemNameInput').val();
        });
        if(selected.length){
            addItemRow({
                ItemCode: selected.data('itemcode'),
                ItemName: selected.val(),
                SalePrice: selected.data('saleprice')
            });
            $('#itemNameInput').val('');
            $('#itemCodeInput').focus();
        }
    });

    // Confirm before form submit
    $('#saleInvoiceForm').on('submit', function(){
        return confirm('Do you want to update this Invoice?');
    });

    // F10 shortcut to submit form
    $(document).on('keydown', function(e){
        if(e.key === 'F10'){
            e.preventDefault();
            $('#saleInvoiceForm').submit();
        }
    });

    // Bind events to existing rows
    $('#itemsTable tbody tr').each(function() {
        let row = $(this);
        row.find('.qty, .rate, .discper').on('input', function(){ calculateRow(row); });
        row.find('.btn-delete').on('click', function(){ deleteRow(row.attr('id')); });
    });

    // --- Functions ---
    function addItemRow(item){
        let table = $('#itemsTable tbody');
        let newRow = `
            <tr id="row-${rowCount}">
                <td><input type="text" name="items[${rowCount}][ItemCode]" class="form-control form-control-sm" value="${item.ItemCode}" readonly></td>
                <td><input type="text" name="items[${rowCount}][ItemName]" class="form-control form-control-sm" value="${item.ItemName}" readonly></td>
                <td><input type="number" step="1" name="items[${rowCount}][Qty]" class="form-control form-control-sm qty" value="1"></td>
                <td><input type="number" step="0.01" name="items[${rowCount}][Rate]" class="form-control form-control-sm rate" value="${item.SalePrice}"></td>
                <td><input type="number" step="0.01" name="items[${rowCount}][DiscPer]" class="form-control form-control-sm discper" value="0"></td>
                <td><input type="number" step="0.01" name="items[${rowCount}][Disc]" class="form-control form-control-sm disc" readonly></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm total" readonly></td>
                <td><button type="button" class="btn btn-danger btn-delete btn-sm">×</button></td>
            </tr>
        `;
        table.append(newRow);
        
        // Bind events for the newly added row
        let row = table.find('tr').last();
        row.find('.qty, .rate, .discper').on('input', function(){ calculateRow(row); });
        row.find('.btn-delete').on('click', function(){ deleteRow(row.attr('id')); });

        calculateRow(row);
        rowCount++;
    }

function deleteRow(rowId){
    if(confirm('Are you sure you want to delete this item?')){
        $(`#${rowId}`).remove();
        calculateGrandTotal(); // Recalculate after deletion
    }
}

    function calculateRow(row){
        let qty = parseFloat(row.find('.qty').val()) || 0;
        let rate = parseFloat(row.find('.rate').val()) || 0;
        let discPer = parseFloat(row.find('.discper').val()) || 0;

        let gross = qty * rate;
        let disc = (gross * discPer)/100;
        let total = gross - disc;

        row.find('.disc').val(disc.toFixed(2));
        row.find('.total').val(total.toFixed(2));
    }
    
    
    // Add these functions to your existing JavaScript

function calculateGrandTotal() {
    let grandTotal = 0;
    $('.total').each(function() { 
        grandTotal += parseFloat($(this).val()) || 0; 
    });
    $('#grandTotal').val(grandTotal.toFixed(2));
    updateNetAmount(); // Update net amount when grand total changes
}

function updateNetAmount() {
    let grandTotal = parseFloat($('#grandTotal').val()) || 0;
    let cashDiscount = parseFloat($('#cashDiscount').val()) || 0;
    let netAmount = grandTotal - cashDiscount;
    
    $('#netAmount').val(netAmount.toFixed(2));
}

// Update the calculateRow function to recalculate grand total
function calculateRow(row){
    let qty = parseFloat(row.find('.qty').val()) || 0;
    let rate = parseFloat(row.find('.rate').val()) || 0;
    let discPer = parseFloat(row.find('.discper').val()) || 0;

    let gross = qty * rate;
    let disc = (gross * discPer)/100;
    let total = gross - disc;

    row.find('.disc').val(disc.toFixed(2));
    row.find('.total').val(total.toFixed(2));
    
    calculateGrandTotal(); // Recalculate grand total after row update
}

// Add event listeners for cash discount and cash received
$('#cashDiscount, #cashReceived').on('input', function() {
    updateNetAmount();
});


    // Calculate initial totals
    $('#itemsTable tbody tr').each(function() {
        calculateRow($(this));
    });
    
    // Set initial net amount
    updateNetAmount();

});
</script>
@endpush