@extends('layouts.app')

@section('title', $storageUnit->name . ' - Profit Tracker')

@section('content')

<div class="header-actions">
    <h1>🏠 {{ $storageUnit->name }}</h1>
    <div style="margin-left: auto;">
        <a href="{{ route('storage-units.edit', $storageUnit) }}" class="button">Edit Unit</a>
        <a href="{{ route('sales.create') }}?storage_unit={{ $storageUnit->id }}" class="button button--success">+ Add Sale</a>
    </div>
</div>

<div class="stats">
    <div class="stat-card">
        <div class="stat-value">${{ number_format($storageUnit->cost, 2) }}</div>
        <div class="stat-label">Purchase Cost</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">${{ number_format($storageUnit->total_sales, 2) }}</div>
        <div class="stat-label">Total Sales</div>
    </div>
    <div class="stat-card">
        <div class="stat-value {{ $storageUnit->net_profit >= 0 ? 'positive' : 'negative' }}">
            ${{ number_format($storageUnit->net_profit, 2) }}
        </div>
        <div class="stat-label">Net Profit</div>
    </div>
    <div class="stat-card">
        <div class="stat-value {{ $storageUnit->roi >= 0 ? 'positive' : 'negative' }}">
            {{ $storageUnit->roi }}%
        </div>
        <div class="stat-label">ROI</div>
    </div>
</div>

        <div class="card">
            <h2>Unit Details</h2>
            <div class="unit-info">
                <div class="info-item">
                    <div class="info-label">Purchase Date</div>
                    <div class="info-value">{{ $storageUnit->purchase_date->format('M j, Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Location</div>
                    <div class="info-value">{{ $storageUnit->location ?: '—' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <span class="status {{ $storageUnit->status }}">
                            {{ ucfirst(str_replace('_', ' ', $storageUnit->status)) }}
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Total Items Sold</div>
                    <div class="info-value">{{ $storageUnit->sales->count() }} items</div>
                </div>
            </div>
            @if($storageUnit->notes)
                <div style="margin-top: 20px;">
                    <div class="info-label">Notes</div>
                    <div class="info-value">{{ $storageUnit->notes }}</div>
                </div>
            @endif
        </div>

        @include('components.sales-table', [
            'sales' => $sales,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
            'perPage' => $perPage,
            'title' => "Sales from this Unit",
            'showStorageUnit' => false,
            'headingLevel' => 'h2',
            'emptyMessage' => 'Start selling items from this storage unit!',
            'emptyAction' => '<a href="' . route('sales.create', ['storage_unit' => $storageUnit->id]) . '" class="button button--success">+ Record First Sale</a>'
        ])

        @if($storageUnit->expenses->count() > 0)
            <div class="card">
                <h2>Related Expenses ({{ $storageUnit->expenses->count() }})</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Category</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($storageUnit->expenses->sortByDesc('date') as $expense)
                            <tr>
                                <td>{{ $expense->description }}</td>
                                <td>${{ number_format($expense->amount, 2) }}</td>
                                <td>{{ $expense->category ?: '—' }}</td>
                                <td>{{ $expense->date->format('M j, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: #f8f9fa; font-weight: 600;">
                            <td>Total Additional Expenses:</td>
                            <td>${{ number_format($storageUnit->expenses->sum('amount'), 2) }}</td>
                            <td colspan="2">{{ $storageUnit->expenses->count() }} expenses</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
@endsection