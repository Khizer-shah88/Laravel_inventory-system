@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Add New Menu</h4>

    <form method="POST" action="{{ route('menus.store') }}">
        @csrf

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Route Name</label>
            <input type="text" name="route" class="form-control">
        </div>

        <div class="mb-3">
            <label>Icon (optional)</label>
            <input type="text" name="icon" class="form-control">
        </div>

        <div class="mb-3">
            <label>Parent Menu</label>
            <select name="parent_id" class="form-control">
                <option value="">None</option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Order</label>
            <input type="number" name="order" class="form-control" value="0">
        </div>

        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection
