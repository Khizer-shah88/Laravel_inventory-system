@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Items in Box: {{ $box->BoxName }}</h2>

   <a href="{{ route('box.sub.create', $box->Id) }}" class="btn btn-primary mb-3">
    Add Item
</a>

    <a href="{{ route('box.index') }}" class="btn btn-secondary mb-3">Back to Boxes</a>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Item Code</th>
            <th>Item Name</th>
            <th>Actions</th>
        </tr>
        @foreach ($items as $item)
        <tr>
            <td>{{ $item->Id }}</td>
            <td>{{ $item->ItemCode }}</td>
            <td>{{ $item->ItemName }}</td>
            <td>
<a href="{{ route('box.sub.edit', [$box->Id, $item->Id]) }}" class="btn btn-warning btn-sm">
    Edit
</a>


<form action="{{ route('box.sub.destroy', [$box->Id, $item->Id]) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
        Delete
    </button>
</form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
