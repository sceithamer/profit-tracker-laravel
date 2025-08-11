@extends('layouts.storage-app')

@section('title', 'All Sales - Profit Tracker')

@section('content')

<div class="header-actions">
    <h1>📋 All Sales</h1>
    <div style="margin-left: auto;">
        @if(auth()->user()->hasPermission('create_sales'))
            <a href="{{ route('sales.create') }}" class="button button--success">+ Add Sale</a>
        @endif
    </div>
</div>

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
        <div class="stat-value {{ $netRevenue >= 0 ? 'positive' : 'negative' }}">${{ number_format($netRevenue, 2) }}</div>
        <div class="stat-label">Net Revenue</div>
    </div>
</div>

@include('components.sales-table', [
    'sales' => $sales,
    'sortBy' => $sortBy,
    'sortDir' => $sortDir,
    'perPage' => $perPage,
    'title' => 'All Sales',
    'emptyMessage' => 'Record your first sale to start tracking profits!',
    'emptyAction' => auth()->user()->hasPermission('create_sales') ? '<a href="' . route('sales.create') . '" class="button button--success">+ Record First Sale</a>' : ''
])
@endsection