@extends('layouts.app')

@section('page_title', 'Purchase Invoice')

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

    <form id="saleInvoiceForm" action="{{ route('PurchaseInvoice.store') }}" method="POST">
        @csrf

        <!-- Master Section -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">Invoice Details</div>
            <div class="card-body row g-2">
                <div class="col-md-4">
                    <label class="form-label">Invoice Date</label>
                    <input type="date" name="InvDate" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Header Code</label>
                    <input type="text" name="HeaderCode" id="HeaderCode" class="form-control" value="100" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Account ID</label>
                    <input type="number" name="AccountId" id="AccountId" class="form-control" value="8" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Account Name</label>
                    <input list="accountsList" id="AccountName" name="AccountName" class="form-control" value="Walking Customer" required>
                    <datalist id="accountsList">
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->AccountName }}" data-headercode="{{ $acc->HeaderCode }}" data-accountid="{{ $acc->AccountId }}"></option>
                        @endforeach
                    </datalist>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Town</label>
                    <input type="text" name="Town" class="form-control">
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
                    @foreach($items as $item)
                        <option value="{{ $item->ItemName }}" data-itemcode="{{ $item->ItemCode }}" data-saleprice="{{ $item->PurPrice }}"></option>
                    @endforeach
                </datalist>
            </div>
        </div>

        <!-- Child Section -->
        <div class="card mb-3">
            <div class="card-header bg-success text-white">Invoice Items</div>
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
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-end">Total Qty:</th>
                            <th><input type="text" id="totalQty" class="form-control form-control-sm" readonly></th>
                            <th colspan="3" class="text-end">Grand Total:</th>
                            <th><input type="text" id="grandTotal" class="form-control form-control-sm" readonly></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="6" class="text-end">Cash Discount:</th>
                            <th><input type="number" step="0.01" id="cashDiscount" name="CashDiscount" class="form-control form-control-sm" value="0"></th>
                            <th></th>
                        </tr>

                        <tr>
                            <th colspan="6" class="text-end">Net Amount:</th>
                            <th><input type="text" id="netAmount" class="form-control form-control-sm" readonly></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="6" class="text-end">Cash Received:</th>
                            <th><input type="number" step="0.01" id="cashReceived" name="CashReceived" class="form-control form-control-sm" value="0"></th>
                            <th></th>
                        </tr>                        
                    </tfoot>

                </table>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Save Invoice</button>
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

    let rowCount = 0;

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
                        addItemRow({ItemCode: data.ItemCode, ItemName: data.ItemName, SalePrice: data.PurPrice});
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
        return confirm('Do you want to save this Invoice?');
    });

    // F10 shortcut to submit form
    $(document).on('keydown', function(e){
        if(e.key === 'F10'){
            e.preventDefault();
            $('#saleInvoiceForm').submit();
        }
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
                <td><input type="number" step="0.01" name="items[${rowCount}][Total]" class="form-control form-control-sm total" readonly></td>
                <td><button type="button" class="btn btn-danger btn-delete btn-sm">×</button></td>
            </tr>
        `;
        table.append(newRow);
        rowCount++;

        // Bind events for the newly added row
        let row = table.find('tr').last();
        row.find('.qty, .rate, .discper').on('input', function(){ calculateRow(row); });
        row.find('.btn-delete').on('click', function(){ deleteRow(row.attr('id')); });

        calculateRow(row);
    }

    function deleteRow(rowId){
        if(confirm('Are you sure you want to delete this item?')){
            $(`#${rowId}`).remove();
            calculateGrandTotal();
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
        calculateGrandTotal();
    }

// Update Net Amount whenever totals or cash discount/received change
function updateNetAmount(){
    let grandTotal = parseFloat($('#grandTotal').val()) || 0;
    let cashDiscount = parseFloat($('#cashDiscount').val()) || 0;
    let cashReceived = parseFloat($('#cashReceived').val()) || 0;

    let net = grandTotal - cashDiscount;
    $('#netAmount').val(net.toFixed(2));
}

// Call updateNetAmount whenever cashDiscount or cashReceived changes
$('#cashDiscount, #cashReceived').on('input', updateNetAmount);

// Update Net Amount whenever Grand Total changes
function calculateGrandTotal(){
    let total = 0, qtyTotal = 0;
    $('.total').each(function(){ total += parseFloat($(this).val()) || 0; });
    $('.qty').each(function(){ qtyTotal += parseFloat($(this).val()) || 0; });

    $('#grandTotal').val(total.toFixed(2));
    $('#totalQty').val(qtyTotal);

    updateNetAmount(); // <-- Add this
}


});
</script>
@endpush

