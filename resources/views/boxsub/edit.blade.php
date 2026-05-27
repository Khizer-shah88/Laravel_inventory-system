@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Item in Box: {{ $box->BoxName }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('box.sub.update', [$box->Id, $item->Id]) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Box Info -->
        <div class="mb-3">
            <label for="BoxName" class="form-label">Box Name</label>
            <input type="text" class="form-control" id="BoxName" value="{{ $box->BoxName }}" disabled>
        </div>

        <!-- Item Code -->
        <div class="mb-3">
            <label for="ItemCode" class="form-label">Item Code</label>
            <input type="text" class="form-control" id="ItemCode" name="ItemCode" 
                   value="{{ old('ItemCode', $item->ItemCode) }}" required>
        </div>

        <!-- Item Name -->
        <div class="mb-3">
            <label for="ItemName" class="form-label">Item Name</label>
            <input type="text" class="form-control" id="ItemName" name="ItemName" 
                   value="{{ old('ItemName', $item->ItemName) }}" required>
        </div>

        <!-- Buttons -->
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('box.sub.index', $box->Id) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
