@extends('layouts.app')
@push('styles')
<style>
@media print {
       nav.navbar {
        display: none !important;
    }
    body {
        font-size: 7pt !important; /* or use 12px if you prefer */
    }
}
</style>

@endpush
@section('page_title','Box List')
@section('content')
<div class="container d-print-none">
<h2 class="mb-3 d-inline-block">Boxes</h2>
<a href="{{ route('box.create') }}" class="btn btn-primary mb-3 ms-3">Add New Box</a>

<form action="{{ route('box.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap mb-3">
    <label for="boxInput" class="mb-0">Select Box:</label>

    <input list="boxList" id="boxInput" name="box" class="form-control w-auto" autocomplete="off" placeholder="Box name">

    <datalist id="boxList">
        @foreach($box as $b)
            <option data-id="{{ $b->Id }}" value="{{ $b->BoxName }}"></option>
        @endforeach
    </datalist>

    <input type="hidden" id="boxID" name="box_id" class="form-control w-auto" placeholder="Box ID" readonly>

    <button type="submit" class="btn btn-primary">Search</button>
</form>
    
@if(isset($results))
    <h5 class="mt-3">Results:</h5>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>Box Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $box)
                <tr>
                    <td>{{ $box->BoxName }}</td>
                    <td>
                        <a href="{{ route('box.edit', $box->Id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('box.destroy', $box->Id) }}" method="POST" style="display:inline-block">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this box?')">Delete</button>
                        </form>
                        <a href="{{ route('box.sub.create', $box->Id) }}" class="btn btn-primary btn-sm ">View Items</a>

                </td>                    
                </tr>
            @endforeach
        </tbody>
    </table>
    </br>
</div>
<div class="container">
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>Item Code</th>
                <th>Item Name</th>
                <th>Box Name</th>
                <th class="d-print-none">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($boxItems as $box)
                <tr>
                    <td>{{ $box->ItemCode }}</td>
                    <td>{{ $box->ItemName }}</td>
                    <td>{{ $box->BoxName }}</td>
                    <td class="d-print-none"><form action="{{ route('box-items.destroy', $box->Id) }}" method="POST" style="display:inline-block">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this box?')">Delete</button>
                        </form></td>
                                       
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const boxInput = document.getElementById('boxInput');
    const boxList = document.getElementById('boxList');
    const boxID = document.getElementById('boxID');

    boxInput.addEventListener('input', function() {
        const val = this.value;
        const option = Array.from(boxList.options).find(opt => opt.value === val);
        
        if (option) {
            boxID.value = option.dataset.id; // fill ID
        } else {
            boxID.value = ''; // clear if not matched
        }
    });
});
</script>
@endpush
@endsection
