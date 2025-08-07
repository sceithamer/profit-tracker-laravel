@extends('layouts.app')

@section('title', 'Platforms - Profit Tracker')

@section('content')

<h1>📱 Sales Platforms</h1>

<div class="card">
    <h2>Quick Add Platform</h2>
    <form method="POST" action="{{ route('platforms.store') }}">
        @csrf
        <div class="form-inline">
            <div class="form-group">
                <label for="name">Platform Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g., eBay, Facebook Marketplace">
                @error('name')
                    <div style="color: #ef4444; font-size: 12px; margin-top: 2px;">{{ $message }}</div>
                @enderror
            </div>
            <x-button type="submit" variant="success">Add Platform</x-button>
        </div>
    </form>
</div>

<div class="card">
    <h2>Current Platforms</h2>
    @if($platforms->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Platform Name</th>
                    <th>Total Sales</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($platforms as $platform)
                    <tr>
                        <td><strong>{{ $platform->name }}</strong></td>
                        <td>{{ $platform->sales_count }} sales</td>
                        <td>
                            <x-button href="{{ route('platforms.show', $platform) }}" size="small">View</x-button>
                            <x-button href="{{ route('platforms.edit', $platform) }}" size="small">Edit</x-button>
                            @if($platform->sales_count == 0)
                                <form method="POST" action="{{ route('platforms.destroy', $platform) }}" style="display: inline;" onsubmit="return confirm('Are you sure?')">
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
            <p>No platforms yet. Add your first selling platform above!</p>
            <p><small>Common platforms: eBay, Facebook Marketplace, Mercari, Amazon, Local Sale, etc.</small></p>
        </div>
    @endif
</div>
@endsection