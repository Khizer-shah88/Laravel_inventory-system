@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Menu Management</h4>
    <a href="{{ route('menus.create') }}" class="btn btn-primary mb-3">Add Menu</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Route</th>
                <th>Icon</th>
                <th>Parent</th>
                <th>Order</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menus as $menu)
            <tr>
                <td>{{ $menu->id }}</td>
                <td>{{ $menu->name }}</td>
                <td>{{ $menu->route }}</td>
                <td>{{ $menu->icon }}</td>
                <td>-</td>
                <td>{{ $menu->order }}</td>
                <td>
                    <form method="POST" action="{{ route('menus.destroy', $menu->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @foreach($subMenus->where('parent_id', $menu->id) as $sub)
            <tr>
                <td>{{ $sub->id }}</td>
                <td>— {{ $sub->name }}</td>
                <td>{{ $sub->route }}</td>
                <td>{{ $sub->icon }}</td>
                <td>{{ $menu->name }}</td>
                <td>{{ $sub->order }}</td>
                <td>
                    <form method="POST" action="{{ route('menus.destroy', $sub->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection
