@extends('layouts.storage-app')

@section('title', 'Edit Sale - Profit Tracker')

@section('content')

<div class="card form-card">
    <h1>✏️ Edit Sale</h1>

            <form method="POST" action="{{ route('sales.update', $sale) }}">
                @csrf
                @method('PUT')
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="item_name">Item Name *</label>
                        <input type="text" id="item_name" name="item_name" value="{{ old('item_name', $sale->item_name) }}" required placeholder="e.g., Vintage Lamp, Nike Shoes, etc." autofocus>
                        @error('item_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sale_price">Sale Price * ($)</label>
                        <input type="number" id="sale_price" name="sale_price" step="0.01" min="0" value="{{ old('sale_price', $sale->sale_price) }}" required placeholder="0.00">
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
                                <option value="{{ $platform->id }}" {{ (old('platform_id', $sale->platform_id) == $platform->id) ? 'selected' : '' }}>
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
                                <option value="{{ $user->id }}" {{ (old('user_id', $sale->user_id) == $user->id) ? 'selected' : '' }}>
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
                                <option value="{{ $unit->id }}" {{ (old('storage_unit_id', $sale->storage_unit_id) == $unit->id) ? 'selected' : '' }}>
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
                        <input type="date" id="sale_date" name="sale_date" value="{{ old('sale_date', $sale->sale_date->format('Y-m-d')) }}" required>
                        @error('sale_date')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row-3">
                    <div class="form-group">
                        <label for="category_id">Category *</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">Choose a category...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (old('category_id', $sale->category_id) == $category->id) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="fees">Fees <span class="optional">($0.00)</span></label>
                        <input type="number" id="fees" name="fees" step="0.01" min="0" value="{{ old('fees', $sale->fees) }}" placeholder="0.00">
                        @error('fees')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="shipping_cost">Shipping Cost <span class="optional">($0.00)</span></label>
                        <input type="number" id="shipping_cost" name="shipping_cost" step="0.01" min="0" value="{{ old('shipping_cost', $sale->shipping_cost) }}" placeholder="0.00">
                        @error('shipping_cost')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            <div style="display: flex; gap: 10px; margin-top: 30px; justify-content: center;">
                <x-button type="submit" variant="success">💾 Update Sale</x-button>
                <x-button href="{{ route('sales.index') }}" variant="secondary">Cancel</x-button>
            </div>
        </form>
        
        <!-- Separate delete form outside the update form -->
        <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--color-border-light);">
            <form method="POST" action="{{ route('sales.destroy', $sale) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this sale? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <x-button type="submit" variant="danger">🗑️ Delete Sale</x-button>
            </form>
        </div>
    </div>

    <script>
        // Auto-focus on item name for editing
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