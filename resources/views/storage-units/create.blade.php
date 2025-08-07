@extends('layouts.app')

@section('title', 'Create Storage Unit - Profit Tracker')

@section('content')

<div class="card form-card">
    <h1>➕ Create New Storage Unit</h1>

            <form method="POST" action="{{ route('storage-units.store') }}">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Storage Unit Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g., Unit 245 - Downtown Storage">
                        @error('name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="purchase_date">Purchase Date *</label>
                        <input type="date" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                        @error('purchase_date')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="cost">Purchase Cost *</label>
                        <input type="number" id="cost" name="cost" step="0.01" min="0" value="{{ old('cost') }}" required placeholder="0.00">
                        @error('cost')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" value="{{ old('location') }}" placeholder="e.g., Storage facility name, city">
                        @error('location')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="sold_out" {{ old('status') == 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    @error('status')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" placeholder="Any notes about this storage unit...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="submit" class="btn">Create Storage Unit</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection