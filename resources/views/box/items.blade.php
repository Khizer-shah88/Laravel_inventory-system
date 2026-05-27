@extends('layouts.app')

@section('content')

@push('styles')
<style>
@media print {
    body {
        margin: 5mm !important;
        font-size: 7pt !important;  /* smaller font size */
        transform: scale(0.85);      /* scale down entire content */
        transform-origin: top left;
    }

    /* Hide anything not needed */
    .d-print-none {
        display: none !important;
    }



    /* Hide navbar, headers */
    nav.navbar, header.navbar, .navbar {
        display: none !important;
    }
}

</style>
@endpush

<h5>Box Items List</h5>

<form method="GET" action="{{ route('box.items') }}" class="mb-4">

<div class="row">
    <div class="col-8">
    <!-- 🔘 Search Type -->
    <div class="mb-3 d-print-none">
        <label class="form-label">Search Type:</label><br>
        @php
            $searchType = request('search_type', 'box_wise');
        @endphp

        @foreach([
            'item_code' => 'Item Code',
            'box_wise' => 'Box Wise',
            'item_name' => 'Item Name Wise',
            'company_name' => 'Company Name Wise',
            'category' => 'Category Wise',
        ] as $value => $label)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="search_type" id="radio_{{ $value }}" value="{{ $value }}"
                    {{ $searchType === $value ? 'checked' : '' }}>
                <label class="form-check-label" for="radio_{{ $value }}">{{ $label }}</label>
            </div>
        @endforeach
    </div>

        <input type="text" id="item_code" name="item_code" class="form-control" value="{{ request('item_code') }}" placeholder="ItemCode">

    <div id="boxWiseFields" class="row g-2 mb-3 d-print-none">
        <div class="col-auto">
            <input type="text" name="start_boxname" class="form-control" value="{{ request('start_boxname') }}" placeholder="Start Box Name">
        </div>
        <div class="col-auto">
            <input type="text" name="end_boxname" class="form-control" value="{{ request('end_boxname') }}" placeholder="End Box Name">
        </div>
    </div>

    <!-- 🔍 Item Name -->
    <div id="itemNameField" class="mb-3 d-print-none">

        <input list="itemList" type="text" id="item_name" name="item_name" class="form-control form-control-sm" value="{{ request('item_name') }}" placeholder="Item Name">

    <datalist id="itemList">
        @foreach($items as $item)
            <option data-itemcode="{{ $item->Barcode }} " value="{{ $item->ItemName }}"></option>
        @endforeach
    </datalist>

    </div>

    <!-- 🏢 Company Name -->
    <div id="companyNameField" class="mb-3 d-print-none">
        <input list="CompanyList" type="text" name="company_name" class="form-control" value="{{ request('company_name') }}" placeholder="Company Name">
        <datalist id="CompanyList">
    @foreach($company as $item)
        <option value="{{ $item->CompanyName }}"></option>
    @endforeach
</datalist>
    </div>

    <!-- 🗂️ Category -->
    <div id="categoryField" class="mb-3 d-print-none">
        <input list="CategoryList" type="text" name="category" class="form-control" value="{{ request('category') }}" placeholder="Category">
                <datalist id="CategoryList">
    @foreach($category as $item)
        <option value="{{ $item->Category }}"></option>
    @endforeach
</datalist>
    </div>

    <!-- 🔘 Buttons -->
    <div class="mb-3 d-print-none">
        <button type="submit" class="btn btn-primary">Search</button>
        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">Print</button>
        <button type="button" class="btn btn-warning" id="resetBtn">Reset</button>
    </div>        
        
    </div>
    <div class="col-6"></div>
</div>

</form>


<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
  <div class="modal-dialog">
<form method="POST" action="{{ route('box.sub.simpleStore') }}" id="addItemForm">

        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <label for="modalSubId" class="form-label">Box ID</label>
                    <input type="text" class="form-control" id="modalSubId" name="SubId" readonly>
                </div>

                <div class="mb-3">
                    <label for="modalBoxName" class="form-label">Box Name</label>
                    <input list="BoxList" type="text" class="form-control" id="modalBoxName" name="BoxName">
                    <datalist id="BoxList">
    @foreach($box as $item)
        <option value="{{ $item->BoxName }}" data-id="{{ $item->Id }}"></option>
    @endforeach
</datalist>
                </div>

                <div class="mb-3">
                    <label for="modalItemCode" class="form-label">Item Code</label>
                    <input type="text" class="form-control" id="modalItemCode" name="ItemCode" readonly>
                </div>

                <div class="mb-3">
                    <label for="modalItemName" class="form-label">Item Name</label>
                    <input type="text" class="form-control" id="modalItemName" name="ItemName" readonly>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Add Item</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </form>
  </div>
</div>

<!-- 📦 Results Table -->
<table class="table table-sm">
    <thead>
        <tr>
            <th style="width:8%;">Box Name</th>
            <th style="width:8%;">ItemCode</th>
            <th>Item Name</th>
            <th>Category</th>
            <th>Type</th>
            <th>Total Stock</th>
            <th>Packets</th>
            <th>Loose</th>
            <th class="d-print-none">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($boxItems as $item)
            @php
                // Calculate packets and loose count
                if (!empty($item->PacketSize) && $item->PacketSize > 0) {
                    $packets = intdiv($item->TotalStock, $item->PacketSize);
                    $loose = $item->TotalStock % $item->PacketSize;
                } else {
                    $packets = 0;
                    $loose = $item->TotalStock;
                }
            @endphp

            <tr>
                <td>{{ $item->BoxName }}</td>
                <td>{{ $item->Barcode }}</td>
                <td>{{ $item->ItemName }}</td>
                <td>{{ $item->Category }}</td>
                <td>{{ $item->PTypeUrdu }}</td>
                <td>{{ $item->TotalStock }}</td>
                <td>{{ $packets }}</td>
                <td>{{ $loose }}</td>

                <td class="d-print-none">
                    <form
                        method="POST"
                        action="{{ route('box.item.delete', $item->SubId) }}"
                        onsubmit="return confirm('Are you sure you want to delete this item?');"
                        style="display: inline;"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>

                    <!-- Add Button -->
                    <button
                        type="button"
                        class="btn btn-sm btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#addItemModal"
                        onclick="fillModal('{{ $item->BoxId }}', '{{ $item->BoxName }}', '{{ $item->Barcode }}', '{{ $item->ItemName }}')"
                    >
                        Add
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="text-align:center;">No items found.</td>
            </tr>
        @endforelse
    </tbody>
</table>


@push('scripts')
<script>




document.getElementById('addItemForm').addEventListener('submit', function(e) {
    e.preventDefault(); // stop immediate form submission

    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Checking...'; // optional visual feedback

    let SubId = document.getElementById('modalSubId').value;
    let ItemCode = document.getElementById('modalItemCode').value;

    // AJAX request to check duplicate
    fetch('{{ route("BoxSub.check") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ SubId, ItemCode })
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            alert('This Box and Item Name already Majood Hai!');
            // Re-enable the button if duplicate found
            submitBtn.disabled = false;
            submitBtn.textContent = 'Add Item';
        } else {
            // No duplicate found, submit the form normally
            submitBtn.textContent = 'Adding...';
            form.submit();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error checking duplicate entry.');
        // Re-enable on error too
        submitBtn.disabled = false;
        submitBtn.textContent = 'Add Item';
    });
});




document.getElementById('modalBoxName').addEventListener('input', function() {
    let input = this.value;
    let list = document.getElementById('BoxList');
    let options = list.querySelectorAll('option');
    let valid = false;

    options.forEach(option => {
        if (option.value === input) {
            valid = true;
        }
    });

    if (!valid) {
        this.setCustomValidity('Please select a valid option from the list.');
    } else {
        this.setCustomValidity('');
    }
});
    function fillModal(subId, boxName, itemCode, itemName) {
        document.getElementById('modalSubId').value = subId;
        document.getElementById('modalBoxName').value = boxName;
        document.getElementById('modalItemCode').value = itemCode;
        document.getElementById('modalItemName').value = itemName;
    }



    document.addEventListener('DOMContentLoaded', function () {
        const fieldGroups = {
            box_wise: document.getElementById('boxWiseFields'),
            item_code: document.getElementById('item_code'),
            item_name: document.getElementById('itemNameField'),
            company_name: document.getElementById('companyNameField'),
            category: document.getElementById('categoryField'),
        };

        function toggleFields() {
            const selectedType = document.querySelector('input[name="search_type"]:checked').value;

            for (const [key, element] of Object.entries(fieldGroups)) {
                element.style.display = (key === selectedType) ? 'block' : 'none';
            }

            // Special case: flex for box_wise layout
            if (selectedType === 'box_wise') {
                fieldGroups['box_wise'].style.display = 'flex';
            }
        }

        // Event listeners for all radios
        document.querySelectorAll('input[name="search_type"]').forEach(radio => {
            radio.addEventListener('change', toggleFields);
        });

        // Initial state
        toggleFields();
    });
    
    document.getElementById('resetBtn').addEventListener('click', function() {
    // Reset all input fields
    document.querySelectorAll('input[type="text"]').forEach(input => input.value = '');

    // Reset radios to default (Box Wise)
    const defaultRadio = document.querySelector('input[name="search_type"][value="box_wise"]');
    if (defaultRadio) {
        defaultRadio.checked = true;
    }

    // Trigger the toggle function to show/hide fields accordingly
    const event = new Event('change');
    defaultRadio.dispatchEvent(event);
});



document.addEventListener("DOMContentLoaded", function () {
    const boxNameInput = document.getElementById("modalBoxName");
    const subIdInput = document.getElementById("modalSubId");
    const options = document.querySelectorAll("#BoxList option");

    // Listen on Box Name input, not Sub ID input
    boxNameInput.addEventListener("input", function () {
        const val = this.value;
        
        // Find the <option> with a matching value
        const option = Array.from(options).find(opt => opt.value === val);

        if (option) {
            // Get the data-id from the matching option
            const boxId = option.dataset.id;

            // Fill the SubId field
            subIdInput.value = boxId;

            // Optional alert
            
        } else {
            // Clear the SubId if no match
            subIdInput.value = "";
        }
    });
});



</script>

@endpush

@endsection
