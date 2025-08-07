@extends('layouts.app')

@section('title', 'Dashboard - Storage Units Profit Tracker')

@section('content')

<h1>Dashboard</h1>

<div class="stats">
    <div class="stat-card">
        <div class="stat-value">${{ number_format($totalInvestment, 2) }}</div>
        <div class="stat-label">Total Investment</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">${{ number_format($totalRevenue, 2) }}</div>
        <div class="stat-label">Total Revenue</div>
    </div>
    <div class="stat-card">
        <div class="stat-value {{ $totalProfit >= 0 ? 'positive' : 'negative' }}">
            ${{ number_format($totalProfit, 2) }}
        </div>
        <div class="stat-label">Net Profit</div>
    </div>
    <div class="stat-card">
        <div class="stat-value {{ $overallROI >= 0 ? 'positive' : 'negative' }}">
            {{ $overallROI }}%
        </div>
        <div class="stat-label">Overall ROI</div>
    </div>
</div>

        <div class="card">
            <h2>Storage Units Performance</h2>
            @if($storageUnits->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Purchase Date</th>
                            <th>Cost</th>
                            <th>Sales</th>
                            <th>Net Profit</th>
                            <th>ROI</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($storageUnits as $unit)
                            <tr>
                                <td>{{ $unit->name }}</td>
                                <td>{{ $unit->purchase_date->format('M j, Y') }}</td>
                                <td>${{ number_format($unit->cost, 2) }}</td>
                                <td>${{ number_format($unit->total_sales, 2) }}</td>
                                <td class="{{ $unit->net_profit >= 0 ? 'positive' : 'negative' }}">
                                    ${{ number_format($unit->net_profit, 2) }}
                                </td>
                                <td class="{{ $unit->roi >= 0 ? 'positive' : 'negative' }}">
                                    {{ $unit->roi }}%
                                </td>
                                <td>
                                    <a href="{{ route('storage-units.show', $unit) }}" class="btn" style="padding: 5px 10px; font-size: 12px;">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No storage units yet. <a href="{{ route('storage-units.create') }}">Create your first one!</a></p>
            @endif
        </div>

        @if($unassignedSales->count() > 0)
            <div class="card">
                <h2>Unassigned Sales ({{ $unassignedSales->count() }})</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Sale Price</th>
                            <th>Platform</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($unassignedSales->take(5) as $sale)
                            <tr>
                                <td>{{ $sale->item_name }}</td>
                                <td>${{ number_format($sale->sale_price, 2) }}</td>
                                <td>{{ $sale->platform->name }}</td>
                                <td>{{ $sale->sale_date->format('M j, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($unassignedSales->count() > 5)
                    <p><a href="{{ route('sales.index') }}">View all {{ $unassignedSales->count() }} unassigned sales</a></p>
                @endif
            </div>
        @endif
@endsection