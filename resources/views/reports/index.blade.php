@extends('layouts.storage-app')

@section('title', 'Reports - Storage Units Profit Tracker')

@section('content')

<div class="page-header">
    <h1>Financial Reports</h1>
    
    @if(count($availableYears) > 1)
        <form method="GET" action="{{ route('reports.index') }}" class="year-selector">
            <label for="year" class="label">Report Year:</label>
            <select name="year" id="year" class="form-select" onchange="this.form.submit()">
                @foreach($availableYears as $availableYear)
                    <option value="{{ $availableYear }}" {{ $availableYear == $year ? 'selected' : '' }}>
                        {{ $availableYear }}
                    </option>
                @endforeach
            </select>
        </form>
    @endif
</div>

@if(empty($availableYears))
    <div class="alert alert-info" role="alert">
        <h2>No Data Available</h2>
        <p>No sales or storage unit data found. Start by <a href="{{ route('storage-units.create') }}">adding your first storage unit</a> or <a href="{{ route('sales.create') }}">recording a sale</a>.</p>
    </div>
@else
    <!-- Financial Overview Section -->
    <section class="report-section">
        <h2>Financial Overview - {{ $year }}</h2>
        
        <div class="table-responsive">
            <table class="report-table" role="table">
                <caption class="sr-only">Monthly financial overview showing units bought, spending, expenses, fees, shipping, sales, and net revenue for {{ $year }}</caption>
                <thead>
                    <tr>
                        <th scope="col">Metric</th>
                        <th scope="col" class="text-center">Jan</th>
                        <th scope="col" class="text-center">Feb</th>
                        <th scope="col" class="text-center">Mar</th>
                        <th scope="col" class="text-center">Apr</th>
                        <th scope="col" class="text-center">May</th>
                        <th scope="col" class="text-center">Jun</th>
                        <th scope="col" class="text-center">Jul</th>
                        <th scope="col" class="text-center">Aug</th>
                        <th scope="col" class="text-center">Sep</th>
                        <th scope="col" class="text-center">Oct</th>
                        <th scope="col" class="text-center">Nov</th>
                        <th scope="col" class="text-center">Dec</th>
                        <th scope="col" class="text-center report-total">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Units Bought</th>
                        @for($month = 1; $month <= 12; $month++)
                            <td class="text-center">{{ $financialOverview['monthly'][$month]['units_bought'] ?? 0 }}</td>
                        @endfor
                        <td class="text-center report-total">{{ $financialOverview['yearly']['units_bought'] }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Total Spent</th>
                        @for($month = 1; $month <= 12; $month++)
                            @php
                                $value = $financialOverview['monthly'][$month]['total_spent'] ?? 0;
                                $displayValue = -$value; // Make costs negative
                                $class = $displayValue < 0 ? 'negative' : ($displayValue > 0 ? 'positive' : '');
                            @endphp
                            <td class="text-center {{ $class }}">
                                <span class="currency" aria-label="{{ $displayValue < 0 ? 'negative ' : '' }}{{ number_format(abs($displayValue), 2) }} dollars">
                                    {{ $displayValue < 0 ? '-' : '' }}${{ number_format(abs($displayValue), 2) }}
                                </span>
                            </td>
                        @endfor
                        @php
                            $yearlyValue = $financialOverview['yearly']['total_spent'];
                            $yearlyDisplayValue = -$yearlyValue;
                            $yearlyClass = $yearlyDisplayValue < 0 ? 'negative' : ($yearlyDisplayValue > 0 ? 'positive' : '');
                        @endphp
                        <td class="text-center report-total {{ $yearlyClass }}">
                            <span class="currency" aria-label="{{ $yearlyDisplayValue < 0 ? 'negative ' : '' }}{{ number_format(abs($yearlyDisplayValue), 2) }} dollars">
                                {{ $yearlyDisplayValue < 0 ? '-' : '' }}${{ number_format(abs($yearlyDisplayValue), 2) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Total Expenses</th>
                        @for($month = 1; $month <= 12; $month++)
                            @php
                                $value = $financialOverview['monthly'][$month]['total_expenses'] ?? 0;
                                $displayValue = -$value; // Make costs negative
                                $class = $displayValue < 0 ? 'negative' : ($displayValue > 0 ? 'positive' : '');
                            @endphp
                            <td class="text-center {{ $class }}">
                                <span class="currency" aria-label="{{ $displayValue < 0 ? 'negative ' : '' }}{{ number_format(abs($displayValue), 2) }} dollars">
                                    {{ $displayValue < 0 ? '-' : '' }}${{ number_format(abs($displayValue), 2) }}
                                </span>
                            </td>
                        @endfor
                        @php
                            $yearlyValue = $financialOverview['yearly']['total_expenses'];
                            $yearlyDisplayValue = -$yearlyValue;
                            $yearlyClass = $yearlyDisplayValue < 0 ? 'negative' : ($yearlyDisplayValue > 0 ? 'positive' : '');
                        @endphp
                        <td class="text-center report-total {{ $yearlyClass }}">
                            <span class="currency" aria-label="{{ $yearlyDisplayValue < 0 ? 'negative ' : '' }}{{ number_format(abs($yearlyDisplayValue), 2) }} dollars">
                                {{ $yearlyDisplayValue < 0 ? '-' : '' }}${{ number_format(abs($yearlyDisplayValue), 2) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Total Fees</th>
                        @for($month = 1; $month <= 12; $month++)
                            @php
                                $value = $financialOverview['monthly'][$month]['total_fees'] ?? 0;
                                $displayValue = -$value; // Make costs negative
                                $class = $displayValue < 0 ? 'negative' : ($displayValue > 0 ? 'positive' : '');
                            @endphp
                            <td class="text-center {{ $class }}">
                                <span class="currency" aria-label="{{ $displayValue < 0 ? 'negative ' : '' }}{{ number_format(abs($displayValue), 2) }} dollars">
                                    {{ $displayValue < 0 ? '-' : '' }}${{ number_format(abs($displayValue), 2) }}
                                </span>
                            </td>
                        @endfor
                        @php
                            $yearlyValue = $financialOverview['yearly']['total_fees'];
                            $yearlyDisplayValue = -$yearlyValue;
                            $yearlyClass = $yearlyDisplayValue < 0 ? 'negative' : ($yearlyDisplayValue > 0 ? 'positive' : '');
                        @endphp
                        <td class="text-center report-total {{ $yearlyClass }}">
                            <span class="currency" aria-label="{{ $yearlyDisplayValue < 0 ? 'negative ' : '' }}{{ number_format(abs($yearlyDisplayValue), 2) }} dollars">
                                {{ $yearlyDisplayValue < 0 ? '-' : '' }}${{ number_format(abs($yearlyDisplayValue), 2) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Total Shipping</th>
                        @for($month = 1; $month <= 12; $month++)
                            @php
                                $value = $financialOverview['monthly'][$month]['total_shipping'] ?? 0;
                                $displayValue = -$value; // Make costs negative
                                $class = $displayValue < 0 ? 'negative' : ($displayValue > 0 ? 'positive' : '');
                            @endphp
                            <td class="text-center {{ $class }}">
                                <span class="currency" aria-label="{{ $displayValue < 0 ? 'negative ' : '' }}{{ number_format(abs($displayValue), 2) }} dollars">
                                    {{ $displayValue < 0 ? '-' : '' }}${{ number_format(abs($displayValue), 2) }}
                                </span>
                            </td>
                        @endfor
                        @php
                            $yearlyValue = $financialOverview['yearly']['total_shipping'];
                            $yearlyDisplayValue = -$yearlyValue;
                            $yearlyClass = $yearlyDisplayValue < 0 ? 'negative' : ($yearlyDisplayValue > 0 ? 'positive' : '');
                        @endphp
                        <td class="text-center report-total {{ $yearlyClass }}">
                            <span class="currency" aria-label="{{ $yearlyDisplayValue < 0 ? 'negative ' : '' }}{{ number_format(abs($yearlyDisplayValue), 2) }} dollars">
                                {{ $yearlyDisplayValue < 0 ? '-' : '' }}${{ number_format(abs($yearlyDisplayValue), 2) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Total Sales</th>
                        @for($month = 1; $month <= 12; $month++)
                            @php
                                $value = $financialOverview['monthly'][$month]['total_sales_gross'] ?? 0;
                                $class = $value < 0 ? 'negative' : ($value > 0 ? 'positive' : '');
                            @endphp
                            <td class="text-center {{ $class }}">
                                <span class="currency" aria-label="{{ $value < 0 ? 'negative ' : '' }}{{ number_format(abs($value), 2) }} dollars">
                                    {{ $value < 0 ? '-' : '' }}${{ number_format(abs($value), 2) }}
                                </span>
                            </td>
                        @endfor
                        @php
                            $yearlyValue = $financialOverview['yearly']['total_sales_gross'];
                            $yearlyClass = $yearlyValue < 0 ? 'negative' : ($yearlyValue > 0 ? 'positive' : '');
                        @endphp
                        <td class="text-center report-total {{ $yearlyClass }}">
                            <span class="currency" aria-label="{{ $yearlyValue < 0 ? 'negative ' : '' }}{{ number_format(abs($yearlyValue), 2) }} dollars">
                                {{ $yearlyValue < 0 ? '-' : '' }}${{ number_format(abs($yearlyValue), 2) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Net Revenue</th>
                        @for($month = 1; $month <= 12; $month++)
                            @php
                                $netRevenue = $financialOverview['monthly'][$month]['net_revenue'] ?? 0;
                                $class = $netRevenue < 0 ? 'negative' : ($netRevenue > 0 ? 'positive' : '');
                            @endphp
                            <td class="text-center {{ $class }}">
                                <span class="currency" aria-label="{{ $netRevenue < 0 ? 'negative ' : '' }}{{ number_format(abs($netRevenue), 2) }} dollars">
                                    {{ $netRevenue < 0 ? '-' : '' }}${{ number_format(abs($netRevenue), 2) }}
                                </span>
                            </td>
                        @endfor
                        @php
                            $yearlyNetRevenue = $financialOverview['yearly']['net_revenue'];
                            $yearlyClass = $yearlyNetRevenue < 0 ? 'negative' : ($yearlyNetRevenue > 0 ? 'positive' : '');
                        @endphp
                        <td class="text-center report-total {{ $yearlyClass }}">
                            <span class="currency" aria-label="{{ $yearlyNetRevenue < 0 ? 'negative ' : '' }}{{ number_format(abs($yearlyNetRevenue), 2) }} dollars">
                                {{ $yearlyNetRevenue < 0 ? '-' : '' }}${{ number_format(abs($yearlyNetRevenue), 2) }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- User Performance Section -->
    @if(!empty($userPerformance))
        <section class="report-section">
            <h2>User Performance - {{ $year }}</h2>
            
            @foreach($userPerformance as $userId => $userData)
                <h3>{{ $userData['name'] }}</h3>
                
                <div class="table-responsive">
                    <table class="report-table" role="table">
                        <caption class="sr-only">Monthly performance metrics for {{ $userData['name'] }} in {{ $year }}</caption>
                        <thead>
                            <tr>
                                <th scope="col">Metric</th>
                                <th scope="col" class="text-center">Jan</th>
                                <th scope="col" class="text-center">Feb</th>
                                <th scope="col" class="text-center">Mar</th>
                                <th scope="col" class="text-center">Apr</th>
                                <th scope="col" class="text-center">May</th>
                                <th scope="col" class="text-center">Jun</th>
                                <th scope="col" class="text-center">Jul</th>
                                <th scope="col" class="text-center">Aug</th>
                                <th scope="col" class="text-center">Sep</th>
                                <th scope="col" class="text-center">Oct</th>
                                <th scope="col" class="text-center">Nov</th>
                                <th scope="col" class="text-center">Dec</th>
                                <th scope="col" class="text-center report-total">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Sales Count</th>
                                @for($month = 1; $month <= 12; $month++)
                                    <td class="text-center">{{ $userData['monthly'][$month]['sales_count'] }}</td>
                                @endfor
                                <td class="text-center report-total">{{ $userData['yearly']['sales_count'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Net Revenue</th>
                                @for($month = 1; $month <= 12; $month++)
                                    @php
                                        $netRevenue = $userData['monthly'][$month]['net_revenue'];
                                        $class = $netRevenue < 0 ? 'negative' : ($netRevenue > 0 ? 'positive' : '');
                                    @endphp
                                    <td class="text-center {{ $class }}">
                                        <span class="currency" aria-label="{{ $netRevenue < 0 ? 'negative ' : '' }}{{ number_format(abs($netRevenue), 2) }} dollars">
                                            {{ $netRevenue < 0 ? '-' : '' }}${{ number_format(abs($netRevenue), 2) }}
                                        </span>
                                    </td>
                                @endfor
                                @php
                                    $yearlyNetRevenue = $userData['yearly']['net_revenue'];
                                    $yearlyClass = $yearlyNetRevenue < 0 ? 'negative' : ($yearlyNetRevenue > 0 ? 'positive' : '');
                                @endphp
                                <td class="text-center report-total {{ $yearlyClass }}">
                                    <span class="currency" aria-label="{{ $yearlyNetRevenue < 0 ? 'negative ' : '' }}{{ number_format(abs($yearlyNetRevenue), 2) }} dollars">
                                        {{ $yearlyNetRevenue < 0 ? '-' : '' }}${{ number_format(abs($yearlyNetRevenue), 2) }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        </section>
    @endif

    <!-- Storage Unit Performance Section -->
    @if(!empty($storageUnitPerformance))
        <section class="report-section">
            <h2>Storage Unit Performance - {{ $year }}</h2>
            
            @foreach($storageUnitPerformance as $unitId => $unitData)
                <h3>{{ $unitData['name'] }}</h3>
                
                <div class="table-responsive">
                    <table class="report-table" role="table">
                        <caption class="sr-only">Monthly performance metrics for {{ $unitData['name'] }} in {{ $year }}</caption>
                        <thead>
                            <tr>
                                <th scope="col">Metric</th>
                                <th scope="col" class="text-center">Jan</th>
                                <th scope="col" class="text-center">Feb</th>
                                <th scope="col" class="text-center">Mar</th>
                                <th scope="col" class="text-center">Apr</th>
                                <th scope="col" class="text-center">May</th>
                                <th scope="col" class="text-center">Jun</th>
                                <th scope="col" class="text-center">Jul</th>
                                <th scope="col" class="text-center">Aug</th>
                                <th scope="col" class="text-center">Sep</th>
                                <th scope="col" class="text-center">Oct</th>
                                <th scope="col" class="text-center">Nov</th>
                                <th scope="col" class="text-center">Dec</th>
                                <th scope="col" class="text-center report-total">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Sales Count</th>
                                @for($month = 1; $month <= 12; $month++)
                                    <td class="text-center">{{ $unitData['monthly'][$month]['sales_count'] }}</td>
                                @endfor
                                <td class="text-center report-total">{{ $unitData['yearly']['sales_count'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Net Revenue</th>
                                @for($month = 1; $month <= 12; $month++)
                                    @php
                                        $netRevenue = $unitData['monthly'][$month]['net_revenue'];
                                        $class = $netRevenue < 0 ? 'negative' : ($netRevenue > 0 ? 'positive' : '');
                                    @endphp
                                    <td class="text-center {{ $class }}">
                                        <span class="currency" aria-label="{{ $netRevenue < 0 ? 'negative ' : '' }}{{ number_format(abs($netRevenue), 2) }} dollars">
                                            {{ $netRevenue < 0 ? '-' : '' }}${{ number_format(abs($netRevenue), 2) }}
                                        </span>
                                    </td>
                                @endfor
                                @php
                                    $yearlyNetRevenue = $unitData['yearly']['net_revenue'];
                                    $yearlyClass = $yearlyNetRevenue < 0 ? 'negative' : ($yearlyNetRevenue > 0 ? 'positive' : '');
                                @endphp
                                <td class="text-center report-total {{ $yearlyClass }}">
                                    <span class="currency" aria-label="{{ $yearlyNetRevenue < 0 ? 'negative ' : '' }}{{ number_format(abs($yearlyNetRevenue), 2) }} dollars">
                                        {{ $yearlyNetRevenue < 0 ? '-' : '' }}${{ number_format(abs($yearlyNetRevenue), 2) }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        </section>
    @endif
@endif

@endsection