@extends('layouts.app')

@section('title', 'Dashboard - Storage Units Profit Tracker')

@section('content')

<h1>Dashboard</h1>

<div class="stats">
    <div class="stat-card">
        <div class="stat-value">{{ number_format($totalSales) }}</div>
        <div class="stat-label">Total Sales</div>
    </div>
    <div class="stat-card">
        <div class="stat-value positive">${{ number_format($grossRevenue, 2) }}</div>
        <div class="stat-label">Gross Revenue</div>
    </div>
    <div class="stat-card">
        <div class="stat-value negative">${{ number_format($totalExpenses, 2) }}</div>
        <div class="stat-label">Total Expenses</div>
    </div>
    <div class="stat-card">
        <div class="stat-value {{ $netRevenue < 0 ? 'negative' : ($netRevenue > 0 ? 'positive' : '') }}">
            ${{ number_format($netRevenue, 2) }}
        </div>
        <div class="stat-label">Net Revenue</div>
    </div>
</div>

<div class="stats">
    <div class="stat-card">
        <div class="stat-value">{{ number_format($currentMonthStats['sales_count']) }}</div>
        <div class="stat-label">This Month Sales</div>
        @if($lastMonthStats['sales_count'] > 0)
            @php
                $salesChange = $currentMonthStats['sales_count'] - $lastMonthStats['sales_count'];
                $salesChangePercent = $lastMonthStats['sales_count'] > 0 ? round(($salesChange / $lastMonthStats['sales_count']) * 100, 1) : 0;
            @endphp
            <div class="stat-comparison {{ $salesChange >= 0 ? 'positive' : 'negative' }}">
                {{ $salesChange >= 0 ? '+' : '' }}{{ $salesChange }} ({{ $salesChangePercent >= 0 ? '+' : '' }}{{ $salesChangePercent }}%)
            </div>
        @endif
    </div>
    <div class="stat-card">
        <div class="stat-value {{ $currentMonthStats['net_revenue'] < 0 ? 'negative' : ($currentMonthStats['net_revenue'] > 0 ? 'positive' : '') }}">
            ${{ number_format($currentMonthStats['net_revenue'], 2) }}
        </div>
        <div class="stat-label">This Month Net Revenue</div>
        @if($lastMonthStats['net_revenue'] != 0)
            @php
                $revenueChange = $currentMonthStats['net_revenue'] - $lastMonthStats['net_revenue'];
                $revenueChangePercent = $lastMonthStats['net_revenue'] != 0 ? round(($revenueChange / abs($lastMonthStats['net_revenue'])) * 100, 1) : 0;
            @endphp
            <div class="stat-comparison {{ $revenueChange >= 0 ? 'positive' : 'negative' }}">
                {{ $revenueChange >= 0 ? '+' : '' }}${{ number_format($revenueChange, 2) }} ({{ $revenueChangePercent >= 0 ? '+' : '' }}{{ $revenueChangePercent }}%)
            </div>
        @endif
    </div>
</div>

<div class="card">
    <h2>Recent Activity</h2>
    @if($recentSales->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Sale Price</th>
                    <th>Seller</th>
                    <th>Platform</th>
                    <th>Storage Unit</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentSales as $sale)
                    <tr>
                        <td>{{ $sale->item_name }}</td>
                        <td>${{ number_format($sale->sale_price, 2) }}</td>
                        <td>{{ $sale->user->name }}</td>
                        <td>{{ $sale->platform->name }}</td>
                        <td>{{ $sale->storageUnit ? $sale->storageUnit->name : 'Unassigned' }}</td>
                        <td>{{ $sale->sale_date->format('M j, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p><a href="{{ route('sales.index') }}">View all sales</a></p>
    @else
        <p>No sales recorded yet. <a href="{{ route('sales.create') }}">Record your first sale!</a></p>
    @endif
</div>

<div class="card">
    <h2>Top 5 Performing Storage Units</h2>
    @if($topStorageUnits->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Purchase Date</th>
                    <th>Gross Revenue</th>
                    <th>Total Expenses</th>
                    <th>Net Revenue</th>
                    <th>ROI</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topStorageUnits as $unit)
                    <tr>
                        <td>{{ $unit->name }}</td>
                        <td>{{ $unit->purchase_date->format('M j, Y') }}</td>
                        <td class="positive">${{ number_format($unit->gross_revenue, 2) }}</td>
                        <td class="negative">${{ number_format($unit->total_expenses, 2) }}</td>
                        <td class="{{ $unit->net_revenue < 0 ? 'negative' : ($unit->net_revenue > 0 ? 'positive' : '') }}">
                            ${{ number_format($unit->net_revenue, 2) }}
                        </td>
                        <td class="{{ $unit->roi < 0 ? 'negative' : ($unit->roi > 0 ? 'positive' : '') }}">
                            {{ $unit->roi }}%
                        </td>
                        <td>
                            <a href="{{ route('storage-units.show', $unit) }}" class="button button--small">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p><a href="{{ route('storage-units.index') }}">View all storage units</a></p>
    @else
        <p>No storage units yet. <a href="{{ route('storage-units.create') }}">Create your first one!</a></p>
    @endif
</div>

<div class="card">
    <h2>Top 5 Performing Sellers</h2>
    @if($topSellers->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Seller</th>
                    <th>Total Sales</th>
                    <th>Net Revenue</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topSellers as $seller)
                    <tr>
                        <td>{{ $seller->name }}</td>
                        <td>{{ number_format($seller->user_sales_count) }} sales</td>
                        <td class="{{ $seller->user_net_revenue < 0 ? 'negative' : ($seller->user_net_revenue > 0 ? 'positive' : '') }}">
                            ${{ number_format($seller->user_net_revenue, 2) }}
                        </td>
                        <td>
                            <a href="{{ route('sales.index') }}?user={{ $seller->id }}" class="button button--small">View Sales</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No sales recorded yet. <a href="{{ route('sales.create') }}">Record your first sale!</a></p>
    @endif
</div>

@endsection