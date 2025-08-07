@extends('layouts.app')

@section('title', 'All Sales - Profit Tracker')

@section('content')

<div class="header-actions">
    <h1>📋 All Sales</h1>
    <div style="margin-left: auto;">
        <a href="{{ route('sales.create') }}" class="btn btn-success">+ Add Sale</a>
    </div>
</div>

<div class="stats">
    <div class="stat-card">
        <div class="stat-value">{{ $sales->total() }}</div>
        <div class="stat-label">Total Sales</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">${{ number_format($sales->sum('sale_price'), 2) }}</div>
        <div class="stat-label">Gross Revenue</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">${{ number_format($sales->sum(function($sale) { return $sale->sale_price - $sale->fees - $sale->shipping_cost; }), 2) }}</div>
        <div class="stat-label">Net Revenue</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $sales->where('storage_unit_id', null)->count() }}</div>
        <div class="stat-label">Unassigned</div>
    </div>
</div>

@include('components.sales-table', [
    'sales' => $sales,
    'sortBy' => $sortBy,
    'sortDir' => $sortDir,
    'perPage' => $perPage,
    'title' => 'All Sales',
    'emptyMessage' => 'Record your first sale to start tracking profits!',
    'emptyAction' => '<a href="' . route('sales.create') . '" class="btn btn-success">+ Record First Sale</a>'
])
@endsection