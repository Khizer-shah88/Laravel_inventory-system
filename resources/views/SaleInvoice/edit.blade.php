@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">

            <!-- Master Information -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-table me-2"></i> Master Information
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label for="headerCode" class="form-label">Header Code</label>
                            <input type="text" class="form-control form-control-sm" id="headerCode"
                                value="{{ $saleInvoice->HeaderCode ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label for="invDate" class="form-label">Invoice Date</label>
                            <input type="date" class="form-control form-control-sm" id="invDate"
                                value="{{ isset($saleInvoice->InvDate) ? date('Y-m-d', strtotime($saleInvoice->InvDate)) : date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="accountId" class="form-label">Account ID</label>
                            <input type="number" class="form-control form-control-sm" id="accountId"
                                value="{{ $saleInvoice->AccountId ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label for="invNo" class="form-label">Invoice No</label>
                            <input type="text" class="form-control form-control-sm" id="invNo"
                                value="{{ $saleInvoice->InvNo ?? 'New' }}" disabled>
                            <input type="hidden" id="invoiceId" value="{{ $saleInvoice->InvNo ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label for="accountName" class="form-label">Account Name</label>
                            <input type="text" class="form-control form-control-sm" name="AccountName" id="accountName"
                                autocomplete="off" minlength="3" required
                                value="{{ $saleInvoice->AccountName ?? old('AccountName') }}">
                            <div id="AccountNameList" class="dropdown-menu w-100" style="max-height:250px; overflow:auto;"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="town" class="form-label">Town</label>
                            <input type="text" class="form-control form-control-sm" id="town"
                                value="{{ $saleInvoice->Town ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <strong>Items</strong>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Pkt Size</th>
                                <th>Pkt Qty</th>
                                <th>Qty</th>
                                <th>Total Qty</th>
                                <th>Rate</th>
                                <th>Total</th>
                                <th class="text-center">Action</th>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control form-control-sm" id="itemCode"></td>
                                <td class="position-relative">
                                    <input type="text" class="form-control form-control-sm" id="itemName" placeholder="Item Name" autocomplete="off">
                                    <div id="ItemNameList" class="dropdown-menu w-100" style="max-height:250px; overflow:auto;"></div>
                                </td>
                                <td><input type="number" class="form-control form-control-sm" id="packetSize" value="1" min="1"></td>
                                <td><input type="number" class="form-control form-control-sm" id="packetQty" value="0" min="0"></td>
                                <td><input type="number" class="form-control form-control-sm" id="qty" value="0" min="0"></td>
                                <td><input type="text" class="form-control form-control-sm" id="totalQty" value="0" disabled></td>
                                <td><input type="number" class="form-control form-control-sm" id="rate" value="0.00" min="0" step="0.01"></td>
                                <td><input type="text" class="form-control form-control-sm" id="total" value="0.00" disabled></td>
                                <td class="text-center">
                                    <button id="addItemBtn" class="btn btn-purple btn-sm">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </td>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody">
                            @if(isset($items) && count($items) > 0)
                                @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->ItemCode }}</td>
                                    <td>{{ $item->ItemName }}</td>
                                    <td><input type="number" class="form-control form-control-sm" value="{{ $item->PacketSize }}" min="1"></td>
                                    <td><input type="number" class="form-control form-control-sm" value="{{ $item->PacketQty }}" min="0"></td>
                                    <td><input type="number" class="form-control form-control-sm" value="{{ $item->Qty }}" min="0"></td>
                                    <td>{{ ($item->PacketSize * $item->PacketQty) + $item->Qty }}</td>
                                    <td><input type="number" class="form-control form-control-sm" value="{{ $item->Rate }}" min="0" step="0.01"></td>
                                    <td>{{ number_format((($item->PacketSize * $item->PacketQty) + $item->Qty) * $item->Rate, 2) }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-danger remove-item">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" class="text-center text-muted">No items added yet</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Actions + Invoice Summary -->
            <div class="row mb-3">
                <div class="col-lg-8 mb-2">
                    <button id="saveInvoiceBtn" class="btn btn-success me-2 mb-1">
                        <i class="fas fa-save me-1"></i> Update Invoice <span class="shortcut-key">F10</span>
                    </button>
                    <button id="newInvoiceBtn" class="btn btn-primary me-2 mb-1">
                        <i class="fas fa-plus me-1"></i> New Invoice <span class="shortcut-key">Ctrl+N</span>
                    </button>
                    <button id="printInvoiceBtn" class="btn btn-info me-2 mb-1" onclick="printInvoice()" title="Print Invoice">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                    <button id="deleteInvoiceBtn" class="btn btn-danger mb-1">
                        <i class="fas fa-trash me-1"></i> Delete Invoice
                    </button>
                </div>
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <i class="fas fa-calculator me-2"></i> Invoice Summary
                        </div>
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Grand Total:</span>
                                <span id="grandTotal">0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Cash Discount:</span>
                                <input type="number" class="text-end form-control form-control-sm" id="cashDiscount" name="CashDiscount" min="0" step="0.01" value="{{ $saleInvoice->CashDiscount ?? 0 }}" style="width: 100px;">
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Cash Received:</span>
                                <input type="number" class="text-end form-control form-control-sm" id="cashReceived" value="0" min="0" step="0.01" style="width: 100px;">
                            </div>
                            <hr class="my-1">
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Net Amount:</span>
                                <span id="netAmount">0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


@push('scripts')

    <script>
    $('#packetQty').on('keypress', function (e) {
    if (e.which === 13) { // 13 = Enter key
        e.preventDefault(); // prevent form submit if inside a form
        $('#qty').focus().select();;
    }
});


$('#itemCode').on('keypress', function (e) {
    if (e.which === 13) { // 13 = Enter key
        e.preventDefault(); // prevent form submit if inside a form
        $('#itemName').focus();
    }
});


$('#rate').on('keypress', function (e) {
    if (e.which === 13) { // 13 = Enter key
        e.preventDefault(); // prevent form submit if inside a form
        $('#addItemBtn').focus();
    }
});

$('#qty').on('keypress', function (e) {
    if (e.which === 13) { // 13 = Enter key
        e.preventDefault(); // prevent form submit if inside a form
        $('#rate').focus().select();
    }
});

function printInvoice() {
    const invoiceId = document.getElementById('invoiceId').value;
    if (!invoiceId) {
        alert('Invoice ID is missing.');
        return;
    }

    // Build the Laravel route dynamically (assuming typical REST route)
    const url = `/SaleInvoice/${invoiceId}`;  // Update if your route is different
    openInvoice(url);
}

function openInvoice(url) {
    window.open(url, '_blank');
}




    
        // ========= Fast dropdown: Items =========
        const $itemInput = $("#itemName");
        const $itemMenu  = $("#ItemNameList");
        const $itemCodeInput = $("#itemCode");
        const $rateInput = $("#rate");
        const $packetSizeInput = $("#packetSize");

        function renderItemMenu(data) {
            $itemMenu.empty();
            if (!data || !data.length) {
                $itemMenu.append('<div class="dropdown-item text-muted">No results found</div>');
                return;
            }
            
            data.forEach(item => {
                const $opt = $('<button type="button" class="dropdown-item text-start"></button>')
                    .data('record', item);
                
                const $content = $('<div class="item-option"></div>');
                
                // First line: ItemName
                $content.append($('<div class="item-name"></div>').text(item.ItemName));
                
                // Second line: ItemCode - PacketSize - USalprice
                const itemCode = item.ItemCode || 'N/A';
                const packetSize = item.PacketSize || 'N/A';
                const usalPrice = item.USalprice || '0.00';
                const detailsText = `${itemCode} | Size: ${packetSize} | Price: $${usalPrice}`;
                $content.append($('<div class="item-details"></div>').text(detailsText));
                
                $opt.append($content);
                $itemMenu.append($opt);
            });
            $itemMenu.children().first().addClass('active');
        }

        function fetchItems(term) {
            if (term.length < 2) { 
                $itemMenu.removeClass('show').hide(); 
                return; 
            }
            
            // Show loading indicator
            $itemMenu.html('<div class="dropdown-item text-muted">Searching...</div>').show();
            
            // Fetch items from server
            $.ajax({
                url: "{{ route('get.items') }}",
                method: "GET",
                data: { 
                    ItemName: term,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    renderItemMenu(data);
                    if (data && data.length) {
                        $itemMenu.addClass('show').show();
                    } else {
                        $itemMenu.removeClass('show').hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching items:", error);
                    $itemMenu.html('<div class="dropdown-item text-danger">Error loading items</div>');
                }
            });
        }

        $itemInput.on('input', function(){ 
            fetchItems(this.value.trim()); 
        });

        $itemMenu.on('click', '.dropdown-item', function(e){
            e.preventDefault();
            const r = $(this).data('record'); 
            if(!r) return;
            
            $itemInput.val(r.ItemName);
            $itemCodeInput.val(r.ItemCode || '');
            $rateInput.val(r.USalprice || '0');
            $packetSizeInput.val(r.PacketSize || '1');
            $itemMenu.removeClass('show').hide();
            
            // Trigger calculations after setting values
            const event = new Event('input', { bubbles: true });
            $rateInput[0].dispatchEvent(event);
            $packetSizeInput[0].dispatchEvent(event);
            
            // Focus on packet quantity
            $('#packetQty').focus().select();
        });

        $(document).on('click', function(e){
            if(!$(e.target).closest('#itemName, #ItemNameList').length){ 
                $itemMenu.removeClass('show').hide(); 
            }
        });

        // Keyboard navigation
        $itemInput.on('keydown', function(e){
            if(!$itemMenu.hasClass('show') || !$itemMenu.is(':visible')) return;
            
            const $items = $itemMenu.find('.dropdown-item');
            let $active = $items.filter('.active');
            
            if(e.key === 'ArrowDown'){
                e.preventDefault();
                if($active.length){ 
                    const $next = $active.next('.dropdown-item'); 
                    if($next.length){ 
                        $active.removeClass('active'); 
                        $next.addClass('active');
                        ensureVisible($itemMenu, $next);
                    }
                } else { 
                    $items.first().addClass('active');
                    ensureVisible($itemMenu, $items.first());
                }
            } else if(e.key === 'ArrowUp'){
                e.preventDefault();
                if($active.length){ 
                    const $prev = $active.prev('.dropdown-item'); 
                    if($prev.length){ 
                        $active.removeClass('active'); 
                        $prev.addClass('active');
                        ensureVisible($itemMenu, $prev);
                    }
                }
            } else if(e.key === 'Enter'){
                e.preventDefault(); 
                if($active.length){ 
                    $active.trigger('click'); 
                }
            } else if(e.key === 'Escape'){
                $itemMenu.removeClass('show').hide();
            }
        });
        
        function ensureVisible($container, $element) {
            const containerHeight = $container.height();
            const containerScrollTop = $container.scrollTop();
            const elementTop = $element.position().top;
            const elementHeight = $element.outerHeight();
            
            if (elementTop < 0) {
                $container.scrollTop(containerScrollTop + elementTop);
            } else if (elementTop + elementHeight > containerHeight) {
                $container.scrollTop(containerScrollTop + (elementTop + elementHeight - containerHeight));
            }
        }

        // Debug: Check if route exists
        console.log("Items route: {{ route('get.items') }}");


    //Account Name
    // Account dropdown functionality
const $input = $('#accountName');
const $list = $('#AccountNameList'); // should be <div class="dropdown-menu" id="AccountNameList"></div>
let selectedIndex = -1;

function fetchAccounts(term) {
    if (term.length < 2) {
        closeDropdown();
        return;
    }

    $.get('/get-accounts', { AccountName: term })
        .done(function(data) {
            renderAccountMenu(data);
            if (data.length) {
                openDropdown();
            } else {
                closeDropdown();
            }
             console.log(data);
        })
       

        .fail(closeDropdown);
}

function openDropdown() {
    selectedIndex = -1;
    $list.addClass('show').show();
}


function closeDropdown() {
    selectedIndex = -1;
    $list.removeClass('show').hide().empty();
}

function renderAccountMenu(accounts) {
    $list.empty();
    accounts.forEach((account) => {
        const $item = $('<a href="#" class="dropdown-item"></a>').text(account.AccountName);
        $item.data('account', account);
        $item.on('click', function(e) {
            e.preventDefault();
            selectAccount($(this));
        });
        $list.append($item);
    });
}

function selectAccount($item) {
    const acc = $item.data('account');
    $('#headerCode').val(acc.HeaderCode);
    $('#accountId').val(acc.AccountId);
    $('#accountName').val(acc.AccountName);
    $('#Town').val(acc.Town);
    closeDropdown();
    $('#itemName').focus();
}

function highlightItem(index) {
    const items = $list.children('.dropdown-item');
    if (!items.length) return;

    items.removeClass('active');

    if (index < 0) {
        selectedIndex = -1;
        return;
    }

    if (index >= items.length) {
        selectedIndex = items.length - 1;
    } else {
        selectedIndex = index;
    }

    const $current = items.eq(selectedIndex);
    $current.addClass('active');

    const listTop = $list.scrollTop();
    const listBottom = listTop + $list.outerHeight();
    const itemTop = $current.position().top + listTop;
    const itemBottom = itemTop + $current.outerHeight();

    if (itemBottom > listBottom) {
        $list.scrollTop(itemBottom - $list.outerHeight());
    } else if (itemTop < listTop) {
        $list.scrollTop(itemTop);
    }
}

function positionDropdown() {
    const inputOffset = $input.offset();
    $list.css({
        position: 'absolute',
        top: inputOffset.top + $input.outerHeight(),
        left: inputOffset.left,
        width: $input.outerWidth(),
        zIndex: 1000
    });
}

$input.on('input', function() {
    fetchAccounts(this.value);
});

$input.on('keydown', function(e) {
    const items = $list.children('.dropdown-item');
    if (!$list.is(':visible') || !items.length) return;

    switch (e.key) {
        case 'ArrowDown':
            e.preventDefault();
            highlightItem(selectedIndex + 1);
            break;
        case 'ArrowUp':
            e.preventDefault();
            highlightItem(selectedIndex - 1);
            break;
        case 'Enter':
            e.preventDefault();
            if (selectedIndex >= 0) {
                selectAccount(items.eq(selectedIndex));
            } else if (items.length) {
                selectAccount(items.eq(0)); // fallback to first
            }
            break;
        case 'Escape':
            closeDropdown();
            break;
    }
});

$(document).on('click', function(e) {
    if (!$(e.target).closest('#AccountNameList, #AccountName').length) {
        closeDropdown();
    }
});

// Focus first
$input.focus();

    //End Account Name



        document.addEventListener('DOMContentLoaded', function() {
            // Array to store items
            let items = [];
            let currentInvoiceId = null;
            let isSaved = false;
            
            // DOM Elements
            const invNoInput = document.getElementById('invNo');
            const invoiceIdInput = document.getElementById('invoiceId');
            const invoiceStatus = document.getElementById('invoiceStatus');
            const saveInvoiceBtn = document.getElementById('saveInvoiceBtn');
            const newInvoiceBtn = document.getElementById('newInvoiceBtn');
            const printInvoiceBtn = document.getElementById('printInvoiceBtn');
            const addItemBtn = document.getElementById('addItemBtn');
            const cashDiscountInput = document.getElementById('cashDiscount');
            const cashReceivedInput = document.getElementById('cashReceived');
            const notesInput = document.getElementById('notes');
            
            // Get CSRF token safely
            function getCsrfToken() {
                const metaTag = document.querySelector('meta[name="csrf-token"]');
                return metaTag ? metaTag.getAttribute('content') : '';
            }
            
            // Safe element access with null checking
            function getElementSafe(id) {
                const element = document.getElementById(id);
                if (!element) {
                    console.error(`Element with ID '${id}' not found`);
                    return null;
                }
                return element;
            }
            
            // Initialize event listeners only if elements exist
            function initializeEventListeners() {
                if (addItemBtn) addItemBtn.addEventListener('click', addItem);
                if (saveInvoiceBtn) saveInvoiceBtn.addEventListener('click', saveInvoice);
                if (newInvoiceBtn) newInvoiceBtn.addEventListener('click', createNewInvoice);
                
                // Update summary when values change
                if (cashDiscountInput) cashDiscountInput.addEventListener('input', updateSummary);
                if (cashReceivedInput) cashReceivedInput.addEventListener('input', updateSummary);
                
                // Auto-calculate when packet fields change
                const packetSize = getElementSafe('packetSize');
                const packetQty = getElementSafe('packetQty');
                const qty = getElementSafe('qty');
                const rate = getElementSafe('rate');
                
                if (packetSize) packetSize.addEventListener('input', calculateTotal);
                if (packetQty) packetQty.addEventListener('input', calculateTotal);
                if (qty) qty.addEventListener('input', calculateTotal);
                if (rate) rate.addEventListener('input', calculateTotal);
            }
            
            // Keyboard shortcuts - FIXED F10 HANDLING
            document.addEventListener('keydown', function(e) {
                // F10 to save invoice
                if (e.key === 'F10' || e.keyCode === 121) {
                    e.preventDefault();
                    saveInvoice();
                    return false;
                }
                
                // Ctrl+N for new invoice
                if (e.ctrlKey && (e.key === 'n' || e.key === 'N')) {
                    e.preventDefault();
                    createNewInvoice();
                    return false;
                }
                

            });
            
            // Function to calculate total quantity and total automatically
            function calculateTotal() {
                const packetSize = getElementSafe('packetSize');
                const packetQty = getElementSafe('packetQty');
                const qty = getElementSafe('qty');
                const rate = getElementSafe('rate');
                const totalQtyInput = getElementSafe('totalQty');
                const totalInput = getElementSafe('total');
                
                if (!packetSize || !packetQty || !qty || !rate || !totalQtyInput || !totalInput) return 0;
                
                const packetSizeVal = parseInt(packetSize.value) || 0;
                const packetQtyVal = parseInt(packetQty.value) || 0;
                const qtyVal = parseInt(qty.value) || 0;
                const rateVal = parseFloat(rate.value) || 0;
                
                const totalQty = (packetSizeVal * packetQtyVal) + qtyVal;
                const total = totalQty * rateVal;
                
                totalQtyInput.value = totalQty;
                totalInput.value = total.toFixed(2);
                
                return total;
            }
            
            // Function to add item
            function addItem() {
                const itemCode = getElementSafe('itemCode');
                const itemName = getElementSafe('itemName');
                const packetSize = getElementSafe('packetSize');
                const packetQty = getElementSafe('packetQty');
                const qty = getElementSafe('qty');
                const rate = getElementSafe('rate');
                const totalInput = getElementSafe('total');
                
                if (!itemCode || !itemName || !packetSize || !packetQty || !qty || !rate || !totalInput) return;
                
                const itemCodeVal = itemCode.value.trim();
                const itemNameVal = itemName.value.trim();
                const packetSizeVal = parseInt(packetSize.value) || 0;
                const packetQtyVal = parseInt(packetQty.value) || 0;
                const qtyVal = parseInt(qty.value) || 0;
                const rateVal = parseFloat(rate.value) || 0;
                const totalQtyVal = (packetSizeVal * packetQtyVal) + qtyVal;
                const totalVal = totalQtyVal * rateVal;
                
                if (!itemCodeVal || !itemNameVal) {
                    alert('Please enter Item Code and Item Name');
                    return;
                }
                
                if (totalQtyVal <= 0) {
                    alert('Total quantity must be greater than 0');
                    return;
                }
                
                if (rateVal <= 0) {
                    alert('Rate must be greater than 0');
                    return;
                }
                
                // Add to items array at the beginning (so it shows on top)
                items.unshift({
                    itemCode: itemCodeVal,
                    itemName: itemNameVal,
                    packetSize: packetSizeVal,
                    packetQty: packetQtyVal,
                    qty: qtyVal,
                    totalQty: totalQtyVal,
                    rate: rateVal,
                    total: totalVal
                });
                
                // Update table
                updateItemsTable();
                
                // Clear input fields
                itemCode.value = '';
                itemName.value = '';
                packetSize.value = 1;
                packetQty.value = 0;
                qty.value = 0;
                rate.value = '';
                
                const totalQtyInput = getElementSafe('totalQty');
                if (totalQtyInput) totalQtyInput.value = 0;
                if (totalInput) totalInput.value = '0.00';
                
                // Focus back to item code
                itemCode.focus();
            }
            
            // Function to update items table with editable fields
            function updateItemsTable() {
                const tableBody = getElementSafe('itemsTableBody');
                if (!tableBody) return;
                
                tableBody.innerHTML = '';
                
                if (items.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="9" class="text-center">No items added yet</td></tr>';
                } else {
                    items.forEach((item, index) => {
                        const row = document.createElement('tr');
                        row.className = 'item-row';
                        row.innerHTML = `
                            <td style="width:10%;">${item.itemCode}</td>
                            <td style="width:30%;">${item.itemName}</td>
                            <td class="editable-cell">
                                <input type="number" value="${item.packetSize}" min="1" data-field="packetSize" data-index="${index}">
                            </td>
                            <td class="editable-cell">
                                <input type="number" value="${item.packetQty}" min="0" data-field="packetQty" data-index="${index}">
                            </td>
                            <td class="editable-cell">
                                <input type="number" value="${item.qty}" min="0" data-field="qty" data-index="${index}">
                            </td>
                            <td>${item.totalQty}</td>
                            <td class="editable-cell">
                                <input type="number" value="${item.rate.toFixed(2)}" min="0" step="0.01" data-field="rate" data-index="${index}">
                            </td>
                            <td>${item.total.toFixed(2)}</td>
                            <td>
                                <button class="btn btn-sm btn-danger remove-item">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                        
                        // Add event listeners to editable fields
                        const inputs = row.querySelectorAll('input');
                        inputs.forEach(input => {
                            input.addEventListener('change', function() {
                                const field = this.getAttribute('data-field');
                                const idx = this.getAttribute('data-index');
                                const value = field === 'rate' ? parseFloat(this.value) : parseInt(this.value);
                                
                                if (!isNaN(value)) {
                                    items[idx][field] = value;
                                    
                                    // Recalculate total quantity and total
                                    items[idx].totalQty = (items[idx].packetSize * items[idx].packetQty) + items[idx].qty;
                                    items[idx].total = items[idx].totalQty * items[idx].rate;
                                    
                                    // Update the table to reflect changes
                                    updateItemsTable();
                                }
                            });
                        });
                        
                        // Add event listener to remove button
                        const removeBtn = row.querySelector('.remove-item');
                        removeBtn.addEventListener('click', function() {
                            items.splice(index, 1);
                            updateItemsTable();
                            updateSummary();
                        });
                    });
                }
                
                updateSummary();
            }
            
            // Function to update summary
            function updateSummary() {
                const grandTotalElement = getElementSafe('grandTotal');
                const cashDiscountValueElement = getElementSafe('cashDiscountValue');
                const cashReceivedValueElement = getElementSafe('cashReceivedValue');
                const netAmountElement = getElementSafe('netAmount');
                
                if (!grandTotalElement || !cashDiscountValueElement || 
                    !cashReceivedValueElement || !netAmountElement || !cashDiscountInput || !cashReceivedInput) return;
                
                const grandTotal = items.reduce((sum, item) => sum + item.total, 0);
                const cashDiscount = parseFloat(cashDiscountInput.value) || 0;
                const cashReceived = parseFloat(cashReceivedInput.value) || 0;
                
                const netAmount = grandTotal - cashDiscount - cashReceived;
                
                grandTotalElement.textContent = grandTotal.toFixed(2);
                cashDiscountValueElement.textContent = cashDiscount.toFixed(2);
                cashReceivedValueElement.textContent = cashReceived.toFixed(2);
                netAmountElement.textContent = netAmount.toFixed(2);
            }
            
            // Function to save invoice
            function saveInvoice() {
                if (items.length === 0) {
                    alert('Please add at least one item to the invoice');
                    return;
                }
                
                // Validate master fields
                const headerCode = getElementSafe('headerCode');
                const accountId = getElementSafe('accountId');
                const accountName = getElementSafe('accountName');
                const invDate = getElementSafe('invDate');
                const town = getElementSafe('town');
                
                if (!headerCode || !accountId || !accountName || !invDate || !town || 
                    !cashDiscountInput || !cashReceivedInput || !notesInput) return;
                
                const headerCodeVal = headerCode.value.trim();
                const accountIdVal = accountId.value.trim();
                const accountNameVal = accountName.value.trim();
                
                if (!headerCodeVal || !accountIdVal || !accountNameVal) {
                    alert('Please fill in all required master fields: Header Code, Account ID, and Account Name');
                    return;
                }
                
                // Prepare data for AJAX request
                const formData = {
                    invDate: invDate.value,
                    headerCode: headerCodeVal,
                    accountId: accountIdVal,
                    accountName: accountNameVal,
                    town: town.value.trim(),
                    cashDiscount: parseFloat(cashDiscountInput.value) || 0,
                    cashReceived: parseFloat(cashReceivedInput.value) || 0,
                    notes: notesInput.value.trim(),
                    items: items
                };
                
                // If we have an existing invoice ID, include it for update
                if (currentInvoiceId) {
                    formData.invoiceId = currentInvoiceId;
                }
                
                // Show loading state
                const originalText = saveInvoiceBtn.innerHTML;
                saveInvoiceBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
                saveInvoiceBtn.disabled = true;
                
                // Get CSRF token safely
                const csrfToken = getCsrfToken();
                
                // AJAX request to save/update invoice
                fetch('/sale-invoice', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update UI with the invoice number
                        currentInvoiceId = data.invNo;
                        if (invoiceIdInput) invoiceIdInput.value = data.invNo;
                        if (invNoInput) invNoInput.value = data.invNo;
                        
                        // Update status
                        isSaved = true;
                        if (invoiceStatus) {
                            invoiceStatus.textContent = 'SAVED';
                            invoiceStatus.className = 'status-indicator status-saved';
                        }
                        
                        // Enable print button
                        if (printInvoiceBtn) printInvoiceBtn.disabled = false;
                        
                        alert('Invoice saved successfully with number: ' + data.invNo);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving the invoice. Please check your connection and try again.');
                })
                .finally(() => {
                    // Reset button state
                    saveInvoiceBtn.innerHTML = originalText;
                    saveInvoiceBtn.disabled = false;
                });
            }
            
            // Function to create a new invoice
            function createNewInvoice() {
                if (confirm('Create a new invoice? Any unsaved changes will be lost.')) {
                    // Reset form
                    const headerCode = getElementSafe('headerCode');
                    const accountId = getElementSafe('accountId');
                    const accountName = getElementSafe('accountName');
                    const town = getElementSafe('town');
                    
                    if (headerCode) headerCode.value = '';
                    if (accountId) accountId.value = '';
                    if (accountName) accountName.value = '';
                    if (town) town.value = '';
                    if (cashDiscountInput) cashDiscountInput.value = 0;
                    if (cashReceivedInput) cashReceivedInput.value = 0;
                    if (notesInput) notesInput.value = '';
                    
                    // Reset items
                    items = [];
                    updateItemsTable();
                    
                    // Reset invoice ID
                    currentInvoiceId = null;
                    if (invoiceIdInput) invoiceIdInput.value = '';
                    if (invNoInput) invNoInput.value = 'New';
                    
                    // Update status
                    isSaved = false;
                    if (invoiceStatus) {
                        invoiceStatus.textContent = 'DRAFT';
                        invoiceStatus.className = 'status-indicator status-draft';
                    }
                    
                    // Disable print button
                    if (printInvoiceBtn) printInvoiceBtn.disabled = true;
                    
                    // Focus on first field
                    if (headerCode) headerCode.focus();
                }
            }
            
            // Initialize the application
            initializeEventListeners();
            updateItemsTable();
            updateSummary();
            calculateTotal();
        });
    </script>
@endpush
@endsection
