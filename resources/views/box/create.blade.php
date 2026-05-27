@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Box</h2>

    <form action="{{ route('box.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Box Name</label>
            <input type="text" name="BoxName" value="{{ old('BoxName') }}"
                   class="form-control @error('BoxName') is-invalid @enderror" required>
                        @error('BoxName')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>


        <button type="submit" name="save_close" class="btn btn-success">Save & Close</button>
        <button type="submit" name="save_next" class="btn btn-primary">Save & Next</button>
        <a href="/box" class="btn btn-danger">Cancel</a>
    </form>
</div>
@endsection
