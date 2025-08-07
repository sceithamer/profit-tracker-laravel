@extends('layouts.app')

@section('title', 'Storage Units - Profit Tracker')

@section('content')

<h1>🏠 Storage Units</h1>

<div class="card">
    @if($storageUnits->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Purchase Date</th>
                    <th>Cost</th>
                    <th>Location</th>
                    <th>Total Sales</th>
                    <th>Net Profit</th>
                    <th>ROI</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($storageUnits as $unit)
                    <tr>
                        <td><strong>{{ $unit->name }}</strong></td>
                        <td>{{ $unit->purchase_date->format('M j, Y') }}</td>
                        <td>${{ number_format($unit->cost, 2) }}</td>
                        <td>{{ $unit->location ?: '—' }}</td>
                        <td>${{ number_format($unit->total_sales, 2) }}</td>
                        <td class="{{ $unit->net_profit >= 0 ? 'positive' : 'negative' }}">
                            ${{ number_format($unit->net_profit, 2) }}
                        </td>
                        <td class="{{ $unit->roi >= 0 ? 'positive' : 'negative' }}">
                            {{ $unit->roi }}%
                        </td>
                        <td>
                            <span class="status {{ $unit->status }}">
                                {{ ucfirst(str_replace('_', ' ', $unit->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('storage-units.show', $unit) }}" class="button button--small">View</a>
                            <a href="{{ route('storage-units.edit', $unit) }}" class="button button--small">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 40px;">
            <h2>No Storage Units Yet</h2>
            <p>Create your first storage unit to start tracking profits!</p>
            <a href="{{ route('storage-units.create') }}" class="button button--success">+ Create Storage Unit</a>
        </div>
    @endif
</div>
@endsection