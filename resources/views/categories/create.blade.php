@extends('layouts.app')

@section('title', 'Create Category - Profit Tracker')

@section('content')

<div class="card form-card">
    <h1>➕ Create New Category</h1>

    <form method="POST" action="{{ route('categories.store') }}">
        @csrf
        
        <div class="form-group">
            <label for="name">Category Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g., Electronics, Clothing, Books" autofocus>
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px; justify-content: center;">
            <x-button type="submit" variant="success">Create Category</x-button>
            <x-button href="{{ route('categories.index') }}" variant="secondary">Cancel</x-button>
        </div>
    </form>
</div>
@endsection