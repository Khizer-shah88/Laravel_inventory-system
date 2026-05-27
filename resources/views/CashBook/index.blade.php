@extends('layouts.app')

@section('content')
<div class="container">
    <h2>CashBook</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('cashbook.create') }}" class="btn btn-primary mb-3">Add New CashBook</a>

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cashbooks as $cashbook)
            <tr>
                <td>{{ $cashbook->CBID }}</td>
                <td>{{ \Carbon\Carbon::parse($cashbook->CBDate)->format('d-m-Y') }}</td>
                <td>
                    <!-- Edit button -->
                    <a href="{{ route('cashbook.edit', $cashbook->CBID) }}" class="btn btn-sm btn-warning">Edit</a> ||

                        <button class="btn btn-sm btn-danger delete-btn" data-id="{{$cashbook->CBID}}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3">No CashBook found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
  <script>
        $(document).ready(function() {
            // Delete button click handler
            $('.delete-btn').on('click', function() {
                const id = $(this).data('id');
                const row = $(this).closest('tr');
                
                // Confirmation dialog
                if (confirm('Are you sure you want to delete Cash Book #' + id + '?\nThis will delete all transactions associated with this cash book.')) {
                    // AJAX request to delete
                    $.ajax({
                        url: '/cashbook/delete-master/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}' // Replace with your CSRF token
                        },
                        success: function(response) {
                            if (response.success) {
                                // Remove the row from the table
                                row.fadeOut(400, function() {
                                    $(this).remove();
                                });
                                alert('Cash book deleted successfully!');
                            } else {
                                alert('Error: ' + response.message);
                            }
                        },
                        error: function(xhr) {
                            alert('Error deleting cash book: ' + xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
@endpush
@endsection
