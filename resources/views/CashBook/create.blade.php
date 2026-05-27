@extends('layouts.app')

@section('content')

<div class="container">
    
<div class="row">
    <!-- Left Inputs -->
    <div class="col-md-4">
        <form id="cashbookForm">
            @csrf
            <input type="hidden" id="editId" name="editId">

            <div class="row g-0 mt-1">
                <label class="form-label">ID#</label>
                <input type="number" class="form-control form-control-sm" id="CBID" name="CBID" readonly>
            </div>

            <div class="row g-0">
                <label class="form-label">Date</label>
                <input type="date" class="form-control form-control-sm" id="CBDate" name="CBDate" value="{{ date('Y-m-d') }}">
            </div>

            <label class="form-label mb-0">Account Info</label>
            <div class="row g-0">
                <div class="col-md-6">
                    <input type="text" class="form-control form-control-sm" id="HeaderCode" name="HeaderCode" readonly>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control form-control-sm" id="AccountId" name="AccountId" readonly>
                </div>
            </div>

<div class="row g-1 align-items-center">
    <div class="col-12 d-flex justify-content-between align-items-center mb-1">
        <label class="form-label mb-0">Account Name</label>
        <span id="balance" class="text-danger"></span>
    </div>
    <div class="col-12">
        <input list="accountsList" id="AccountName" name="AccountName" class="form-control form-control-sm">
        <datalist id="accountsList">
            @foreach($accounts as $account)
                <option value="{{ $account->AccountName.' ('.$account->AccountType.')' }}"
                        data-id="{{ $account->AccountId }}"
                        data-header="{{ $account->HeaderCode }}">
                    {{ $account->Town.' - '.$account->DSF }}
                </option>
            @endforeach
        </datalist>
    </div>
</div>



            <div class="row g-0">
                <label class="form-label">Debit</label>
                <input type="number" class="form-control form-control-sm" id="Debit" name="Debit" value="0">
            </div>

            <div class="row g-0">
                <label class="form-label">Credit</label>
                <input type="number" class="form-control form-control-sm" id="Credit" name="Credit" value="0">
            </div>

            <div class="row g-0 mb-2">
                <label class="form-label">Description</label>
                <input type="text" class="form-control form-control-sm" id="Description" name="Description">
            </div>

            <button type="button" id="saveBtn" class="btn btn-success btn-sm">Add / Update</button>
        </form>
    </div>

    <!-- Right Table -->
    <div class="col-md-8">

                
        <p class="text-end"><strong>Opening Balance: {{ number_format($openingBal ?? 0) }}</strong></p>
        <table class="table table-bordered table-sm" id="subsTable">
            <thead>
                <tr>
                    <th>Acc-Code</th>
                    <th>AccountName</th>
                    <th>Description</th>
                    <th class="text-end">Debit</th>
                    <th class="text-end">Credit</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Totals:</th>
                    <th class="text-end" id="totalDebit">0</th>
                    <th class="text-end" id="totalCredit">0</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>

        <p class="text-end"><strong>Cash In Hand: <span id="cashInHand">0</span></strong></p>
    </div>
</div>
</div>

@push('scripts')
<script>
    // Make sure openingBal is a raw number
    var openingBal = parseFloat({{ (float) $openingBal ?? 0 }});

    $(document).ready(function () {

        // ---------- ACCOUNT AUTOCOMPLETE ----------
document.getElementById("AccountName")?.addEventListener("input", function () {
    let val = this.value;
    let option = [...document.getElementById("accountsList").options]
        .find(opt => opt.value === val);

    if (option) {
        const accountId = option.dataset.id;
        const headerCode = option.dataset.header;

        document.getElementById("AccountId").value = accountId;
        document.getElementById("HeaderCode").value = headerCode;

        // Fetch balance
        fetch(`/ledger-balance/${accountId}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById("balance").textContent = "Bal: " + parseFloat(data.balance || 0).toFixed(0);

            })
            .catch(err => console.error(err));
    } else {
        document.getElementById("AccountId").value = "";
        document.getElementById("HeaderCode").value = "";
        document.getElementById("balance").value = "";
    }
});


        // ---------- RESET FORM ----------
        function resetForm() {
            $('#editId').val('');
            $('#HeaderCode').val('');
            $('#AccountId').val('');
            $('#AccountName').val('');
            $('#Description').val('');
            $('#Debit').val(0);
            $('#Credit').val(0);
            $('#saveBtn').text('Add / Update');
        }

        // ---------- SAVE / UPDATE ----------
        $('#saveBtn').on('click', function () {
            let CBID = $('#CBID').val();
            let editId = $('#editId').val();

            let formData = {
                _token: "{{ csrf_token() }}",
                CBID: CBID,
                CBDate: $('#CBDate').val(),
                HeaderCode: $('#HeaderCode').val(),
                AccountId: $('#AccountId').val(),
                AccountName: $('#AccountName').val(),
                Description: $('#Description').val(),
                Debit: parseFloat($('#Debit').val() || 0),
                Credit: parseFloat($('#Credit').val() || 0)
            };

            if(editId) {
                // UPDATE
                $.post(`/cashbook/update/${editId}`, formData, function(response) {
                    if(response.success) {
                        loadSubs(CBID);
                        resetForm();
                    } else {
                        alert("Error: " + response.message);
                    }
                });
            } else {
                // INSERT
                $.post("{{ route('cashbook.store') }}", formData, function(response) {
                    if(response.success) {
                        $('#CBID').val(response.CBID);
                        loadSubs(response.CBID);
                        resetForm();
                    } else {
                        alert("Error: " + response.message);
                    }
                });
            }
        });

        // ---------- LOAD SUBS + TOTALS + CASH IN HAND ----------
        function loadSubs(CBID) {
            $.get(`/cashbook/get-subs/${CBID}`, function(data) {
                let subs = data.subs || [];
                let totals = data.totals || { totalDebit: 0, totalCredit: 0 };

                let tbody = $('#subsTable tbody');
                tbody.empty();

                subs.forEach(sub => {
                    tbody.append(`
                        <tr>
                            <td>${sub.HeaderCode} - ${sub.AccountId}</td>
                            <td>${sub.AccountName}</td>
                            <td>${sub.Description || ''}</td>
                            <td class="text-end">${parseFloat(sub.Debit || 0).toLocaleString()}</td>
                            <td class="text-end">${parseFloat(sub.Credit || 0).toLocaleString()}</td>
                            <td class="text-end">
                                <i class="fas fa-edit editBtn" data-id="${sub.Id}" style="cursor:pointer; color:#0d6efd; margin-right:5px;"></i> || 
                                <i class="fas fa-trash-alt deleteBtn" data-id="${sub.Id}" style="cursor:pointer; color:#dc3545;"></i>
                            </td>


                        </tr>
                    `);
                });

                // Totals
                let totalDebitNum = parseFloat(totals.totalDebit || 0);
                let totalCreditNum = parseFloat(totals.totalCredit || 0);

                $('#totalDebit').text(totalDebitNum.toLocaleString());
                $('#totalCredit').text(totalCreditNum.toLocaleString());

                // Cash In Hand = Opening Balance + Credit - Debit
                let cashInHand = openingBal + totalCreditNum - totalDebitNum;
                $('#cashInHand').text(cashInHand.toLocaleString());
            });
        }

        // ---------- EDIT ROW ----------
        $(document).on('click', '.editBtn', function() {
            let id = $(this).data('id');
            $.get(`/cashbook/sub/${id}`, function(sub) {
                $('#editId').val(sub.Id);
                $('#HeaderCode').val(sub.HeaderCode);
                $('#AccountId').val(sub.AccountId);
                $('#AccountName').val(sub.AccountName);
                $('#Description').val(sub.Description);
                $('#Debit').val(parseFloat(sub.Debit || 0));
                $('#Credit').val(parseFloat(sub.Credit || 0));
                $('#saveBtn').text('Update Entry');
            });
        });

        // ---------- DELETE ROW ----------
        $(document).on('click', '.deleteBtn', function() {
            let id = $(this).data('id');
            let CBID = $('#CBID').val();
            if(confirm('Are you sure you want to delete this row?')) {
                $.ajax({
                    url: `/cashbook/delete/${id}`,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        loadSubs(CBID);
                    }
                });
            }
        });

        // ---------- INITIAL LOAD ----------
        let initialCBID = $('#CBID').val();
        if(initialCBID) loadSubs(initialCBID);
    });
</script>
@endpush

@endsection
