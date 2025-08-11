@extends('layouts.storage-app')

@section('title', 'Categories - Profit Tracker')

@section('content')

<h1>📂 Sales Categories</h1>

@if(auth()->user()->hasPermission('create_categories'))
<div class="card">
    <h2>Quick Add Category</h2>
    <form method="POST" action="{{ route('categories.store') }}">
        @csrf
        <div class="form-inline">
            <div class="form-group">
                <label for="name">Category Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g., Electronics, Clothing, Books">
                @error('name')
                    <div style="color: #ef4444; font-size: 12px; margin-top: 2px;">{{ $message }}</div>
                @enderror
            </div>
            <x-button type="submit" variant="success">Add Category</x-button>
        </div>
    </form>
</div>
@endif

<div class="card">
    <h2>Current Categories</h2>
    @if($categories->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th>Total Sales</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td>{{ $category->sales_count }} sales</td>
                        <td>
                            <x-button href="{{ route('categories.show', $category) }}" size="small">View</x-button>
                            @if(auth()->user()->hasPermission('edit_categories'))
                                <x-button href="{{ route('categories.edit', $category) }}" size="small">Edit</x-button>
                            @endif
                            @if($category->sales_count == 0 && auth()->user()->hasPermission('delete_categories'))
                                <form method="POST" action="{{ route('categories.destroy', $category) }}" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="danger" size="small">Delete</x-button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 20px; color: #666;">
            <p>No categories yet. Add your first category above!</p>
            <p><small>Common categories: Electronics, Clothing, Books, Furniture, etc.</small></p>
        </div>
    @endif
</div>
@endsection