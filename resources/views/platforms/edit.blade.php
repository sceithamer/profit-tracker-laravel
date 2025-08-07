@extends('layouts.app')

@section('title', 'Edit Platform - Profit Tracker')

@section('content')

<div class="card form-card">
    <h1>📱 Edit Platform</h1>
    
    <form method="POST" action="{{ route('platforms.update', $platform) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Platform Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $platform->name) }}" required placeholder="e.g., eBay, Facebook Marketplace" autofocus>
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px; justify-content: center;">
            <button type="submit" class="btn btn-success">Update Platform</button>
            <a href="{{ route('platforms.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection