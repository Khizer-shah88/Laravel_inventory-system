@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Box</h2>

    <form action="{{ route('box.update', $box->Id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Box Name</label>
            <input type="text" name="BoxName" value="{{ old('BoxName', $box->BoxName) }}"
                   class="form-control @error('BoxName') is-invalid @enderror" required>
                    @error('BoxName')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>


        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('box.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
