@extends('layouts.app')

@section('title', $platform->name . ' - Platform Sales')

@section('content')

<div class="header-actions">
    <h1>📱 {{ $platform->name }}</h1>
    <div style="margin-left: auto;">
        <a href="{{ route('platforms.edit', $platform) }}" class="btn">Edit Platform</a>
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
        <div class="stat-value">${{ number_format($sales->sum('fees') + $sales->sum('shipping_cost'), 2) }}</div>
        <div class="stat-label">Total Fees & Shipping</div>
    </div>
</div>

@include('components.sales-table', [
    'sales' => $sales,
    'sortBy' => $sortBy,
    'sortDir' => $sortDir,
    'perPage' => $perPage,
    'title' => "All Sales on {$platform->name}",
    'showPlatform' => false,
    'emptyMessage' => "No items have been sold on {$platform->name} yet.",
    'emptyAction' => '<a href="' . route('sales.create') . '" class="btn btn-success">+ Record First Sale</a>'
])
@endsection