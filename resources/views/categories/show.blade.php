@extends('layouts.app')

@section('title', $category->name . ' - Category Sales')

@section('content')

<div class="header-actions">
    <h1>📂 {{ $category->name }}</h1>
    <div style="margin-left: auto;">
        <x-button href="{{ route('categories.edit', $category) }}">Edit Category</x-button>
    </div>
</div>

@php
    $totalSales = $category->sales()->count();
    $grossRevenue = $category->sales()->sum('sale_price');
    $totalExpenses = $category->sales()->sum('fees') + $category->sales()->sum('shipping_cost');
    $netRevenue = $grossRevenue - $totalExpenses;
@endphp

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

@php
    $emptyAction = '<x-button href="' . route('sales.create') . '" variant="success">+ Record First Sale</x-button>';
@endphp

@include('components.sales-table', [
    'sales' => $sales,
    'sortBy' => $sortBy,
    'sortDir' => $sortDir,
    'perPage' => $perPage,
    'title' => "All Sales in {$category->name}",
    'showPlatform' => true,
    'emptyMessage' => "No items have been sold in the {$category->name} category yet.",
    'emptyAction' => $emptyAction
])
@endsection