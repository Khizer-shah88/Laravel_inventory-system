@extends('layouts.app')
@section('page_title', 'List Of Journal Entry')
@section('content')
<div class="container">
    <h2>Journal Entries</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('journal.create') }}" class="btn btn-primary mb-3">Add New Journal</a>

    <table class="table table-bordered table-sm">
        <thead>
            <tr>

                <th>ID</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($journals as $journal)
            <tr>
                
                <td>{{ $journal->CBID }}</td>
                <td>{{ \Carbon\Carbon::parse($journal->CBDate)->format('d-m-Y') }}</td>
                <td>
                    <a href="{{ route('journal.edit', $journal->CBID) }}" class="btn btn-sm btn-info">Edit</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No journals found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
