@extends('layouts.app')
@section('page_title', 'Accounts List')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <form method="GET" action="{{ route('Accounts.index') }}" class="d-flex">
        <input type="text" name="search" class="form-control me-2 form-control-sm " placeholder="Search accounts..." value="{{ request('search') }}">
        <button class="btn btn-outline-primary btn-sm">Search</button>
    </form>
    <a href="{{ route('Accounts.create') }}" class="btn btn-sm btn-primary ms-2">+ Add Account</a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-sm table-striped">
    <thead>
        <tr>
            <th style="width:10%;">Acc-ID fahad</th>
            <th style="width:15%;">Account Type</th>
            <th>Account Name</th>
            <th>DSF Name</th>
            <th>Town</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($accounts as $account)
        <tr>
            <td>{{ $account->HeaderCode .'-'. $account->AccountId }}</td>
            <td>{{ $account->AccountType }}</td>
            <td>{{ $account->AccountName }}</td>
            <td>{{ $account->DSF }}</td>
            <td>{{ $account->Town }}</td>
<td>
    <!-- Edit -->
    <a href="{{ route('Accounts.edit', $account->AccountId) }}" 
       class="text-warning me-2" title="Edit">
        <i class="fas fa-edit"></i>
    </a>

    <!-- Delete -->
    <form action="{{ route('Accounts.destroy', $account->AccountId) }}" 
          method="POST" style="display:inline-block">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-link text-danger p-0 m-0" 
                onclick="return confirm('Delete this account?')" title="Delete">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</td>

        </tr>
        @empty
        <tr><td colspan="5" class="text-center">No Accounts Found</td></tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-between align-items-center mt-2">
    <div>
        Showing {{ $accounts->firstItem() }} to {{ $accounts->lastItem() }} of {{ $accounts->total() }} results
    </div>
    <div>
        {{ $accounts->withQueryString()->links('pagination::simple-bootstrap-5') }}
    </div>
</div>



@endsection
