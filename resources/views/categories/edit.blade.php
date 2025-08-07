@extends('layouts.app')

@section('title', 'Edit Category - Profit Tracker')

@section('content')

<div class="card form-card">
    <h1>📂 Edit Category</h1>
    
    <form method="POST" action="{{ route('categories.update', $category) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Category Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required placeholder="e.g., Electronics, Clothing, Books" autofocus>
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px; justify-content: center;">
            <x-button type="submit" variant="success">Update Category</x-button>
            <x-button href="{{ route('categories.index') }}" variant="secondary">Cancel</x-button>
        </div>
    </form>
</div>
@endsection