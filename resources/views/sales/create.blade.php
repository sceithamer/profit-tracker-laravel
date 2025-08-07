@extends('layouts.app')

@section('title', 'Quick Sale Entry - Profit Tracker')

@section('content')

<div class="card form-card">
    <h1>⚡ Quick Sale Entry</h1>

            <div class="highlight">
                <strong>💡 Pro Tip:</strong> Log your sales as soon as they happen for accurate tracking!
            </div>

            <form method="POST" action="{{ route('sales.store') }}">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="item_name">Item Name *</label>
                        <input type="text" id="item_name" name="item_name" value="{{ old('item_name') }}" required placeholder="e.g., Vintage Lamp, Nike Shoes, etc." autofocus>
                        @error('item_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sale_price">Sale Price * ($)</label>
                        <input type="number" id="sale_price" name="sale_price" step="0.01" min="0" value="{{ old('sale_price') }}" required placeholder="0.00">
                        @error('sale_price')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="platform_id">Platform *</label>
                        <select id="platform_id" name="platform_id" required>
                            <option value="">Choose selling platform...</option>
                            @foreach($platforms as $platform)
                                <option value="{{ $platform->id }}" {{ old('platform_id') == $platform->id ? 'selected' : '' }}>
                                    {{ $platform->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('platform_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="user_id">Sold By *</label>
                        <select id="user_id" name="user_id" required>
                            <option value="">Choose who sold it...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="storage_unit_id">Storage Unit <span class="optional">(Optional)</span></label>
                        <select id="storage_unit_id" name="storage_unit_id">
                            <option value="">No specific unit / Unassigned</option>
                            @foreach($storageUnits as $unit)
                                <option value="{{ $unit->id }}" {{ (old('storage_unit_id') ?? request('storage_unit')) == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }} (Purchased: {{ $unit->purchase_date->format('M Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('storage_unit_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sale_date">Sale Date *</label>
                        <input type="date" id="sale_date" name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}" required>
                        @error('sale_date')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row-3">
                    <div class="form-group">
                        <label for="item_category">Category <span class="optional">(Optional)</span></label>
                        <input type="text" id="item_category" name="item_category" value="{{ old('item_category') }}" placeholder="e.g., Electronics, Clothing">
                        @error('item_category')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="fees">Fees <span class="optional">($0.00)</span></label>
                        <input type="number" id="fees" name="fees" step="0.01" min="0" value="{{ old('fees') }}" placeholder="0.00">
                        @error('fees')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="shipping_cost">Shipping Cost <span class="optional">($0.00)</span></label>
                        <input type="number" id="shipping_cost" name="shipping_cost" step="0.01" min="0" value="{{ old('shipping_cost') }}" placeholder="0.00">
                        @error('shipping_cost')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            <div style="display: flex; gap: 10px; margin-top: 30px; justify-content: center;">
                <button type="submit" class="btn btn-success">💰 Record Sale</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        // Auto-focus on item name for super fast entry
        document.getElementById('item_name').focus();
        
        // Enter key navigation for faster data entry
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                const inputs = Array.from(document.querySelectorAll('input, select'));
                const currentIndex = inputs.indexOf(document.activeElement);
                if (currentIndex > -1 && currentIndex < inputs.length - 1) {
                    e.preventDefault();
                    inputs[currentIndex + 1].focus();
                }
            }
        });
    </script>
@endsection