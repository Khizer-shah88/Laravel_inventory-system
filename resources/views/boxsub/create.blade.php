@extends('layouts.app')

@section('content')
<div class="container">
    
    <h2>Add Item to Box: {{ $box->BoxName }}</h2>

<div class="row">
    <div class="col-6">
        <form action="{{ route('box.sub.store', $box->Id) }}" method="POST">
        @csrf

        <!-- Show BoxId and BoxName (readonly) -->
        <div class="mb-3">
            <label for="BoxId" class="form-label">Box ID</label>
            <input type="text" class="form-control" value="{{ $box->Id }}" readonly>
        </div>

        <div class="mb-3">
            <label for="BoxName" class="form-label">Box Name</label>
            <input type="text" class="form-control" value="{{ $box->BoxName }}" readonly>
        </div>

        <!-- Hidden field for BoxId -->
        <input type="hidden" name="BoxId" value="{{ $box->Id }}">

        <!-- Item fields -->
        <div class="mb-3">
            <label for="ItemCode" class="form-label">Item Code</label>
            <input type="text" list="itemCodeList" class="form-control" id="ItemCode" name="ItemCode" required>
            <datalist id="itemCodeList">
                @foreach($items as $item)
                    <option value="{{ $item->Barcode }}" data-code="{{ $item->ItemName }}">
                        {{ $item->ItemName }}
                    </option>
                @endforeach
            </datalist>
        </div>

        <div class="mb-3">
            <label for="ItemName" class="form-label">Item Name</label>
            <input list="itemList" name="ItemName" id="ItemName"
                class="form-control @error('ItemName') is-invalid @enderror"
                value="{{ old('ItemName') }}">
        
            <datalist id="itemList">
                @foreach($items as $item)
                    <option value="{{ $item->ItemName }}" data-code="{{ $item->Barcode }}">
                        {{ $item->Barcode }}
                    </option>
                @endforeach
            </datalist>
        
            @error('ItemName')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Buttons -->
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('box.sub.index', $box->Id) }}" class="btn btn-secondary">Cancel</a>
    </form>
    </div>
    <div class="col-6">
        <table class="table">
    <thead>
        <tr>
            <th>ItemCode</th>
            <th>ItemName</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($boxsub as $data)
            <tr id="row-{{ $data->Id }}">
                <td>{{ $data->ItemCode }}</td>
                <td>{{ $data->ItemName }}</td>
                <td>
                    <button 
                        type="button" 
                        class="btn btn-danger deleteBtn" 
                        data-id="{{ $data->Id }}">
                        Delete
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

    </div>
</div>

    
</div>
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const itemNameInput = document.getElementById("ItemName");
    const itemCodeInput = document.getElementById("ItemCode");
    const options = document.querySelectorAll("#itemCodeList option");

    itemCodeInput.addEventListener("input", function () {
        let val = this.value;
        let option = Array.from(options).find(opt => opt.value === val);

        if (option) {
            itemNameInput.value = option.dataset.code;
        } else {
            itemNameInput.value = ""; // clear if no match
        }
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const itemNameInput = document.getElementById("ItemName");
    const itemCodeInput = document.getElementById("ItemCode");
    const options = document.querySelectorAll("#itemList option");

    itemNameInput.addEventListener("input", function () {
        let val = this.value;
        let option = Array.from(options).find(opt => opt.value === val);

        if (option) {
            itemCodeInput.value = option.dataset.code;
        } else {
            itemCodeInput.value = ""; // clear if no match
        }
    });
});

document.querySelectorAll(".deleteBtn").forEach(button => {
    button.addEventListener("click", function() {
        let id = this.dataset.id;

        if (!confirm("Are you sure you want to delete this item?")) return;

        fetch(`/boxsub/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        })
        .then(res => {
            if (!res.ok) {
                throw new Error("Server error " + res.status);
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                document.getElementById(`row-${id}`).remove();
            } else {
                alert(data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert("Something went wrong: " + err.message);
        });
    });
});
</script>
@endpush

@endsection
