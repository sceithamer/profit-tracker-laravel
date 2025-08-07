{{-- 
Sales Table Component

Usage:
@include('components.sales-table', [
    'sales' => $sales,
    'sortBy' => $sortBy,
    'sortDir' => $sortDir,
    'perPage' => $perPage,
    'showStorageUnit' => true,  // optional, defaults to true
    'showPlatform' => true,     // optional, defaults to true
    'title' => 'Custom Title',  // optional, defaults to 'Sales'
    'headingLevel' => 'h2'      // optional, defaults to 'h3', set to match page hierarchy
])
--}}

@php
    $showStorageUnit = $showStorageUnit ?? true;
    $showPlatform = $showPlatform ?? true;
    $title = $title ?? 'Sales';
    $headingLevel = $headingLevel ?? 'h3';
@endphp

<div class="card">
    <div class="card-header">
        <{{ $headingLevel }} class="card-title">{{ $title }} <span class="count">({{ $sales->total() }})</span></{{ $headingLevel }}>
        
        @if($sales->total() > 0)
            @include('components.modern-pagination', [
                'paginator' => $sales,
                'showPerPage' => true,
                'perPage' => $perPage
            ])
        @endif
    </div>

    @if($sales->count() > 0)
        <div class="table-container" role="region" aria-label="Sales data table">
            <table role="table" aria-label="{{ $title }} - Sortable data table">
                <caption class="sr-only">
                    {{ $title }} showing {{ $sales->total() }} sales records. 
                    Table is sortable by clicking column headers. 
                    Currently sorted by {{ str_replace('_', ' ', $sortBy) }} {{ $sortDir === 'desc' ? 'descending' : 'ascending' }}.
                </caption>
                <thead>
                    <tr>
                        <th scope="col">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'sale_date', 'direction' => $sortBy === 'sale_date' && $sortDir === 'desc' ? 'asc' : 'desc']) }}" 
                               style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 5px;"
                               aria-label="Sort by date {{ $sortBy === 'sale_date' && $sortDir === 'desc' ? 'ascending' : 'descending' }}"
                               aria-describedby="sort-help">
                                Date
                                @if($sortBy === 'sale_date')
                                    <span aria-label="{{ $sortDir === 'desc' ? 'sorted descending' : 'sorted ascending' }}" 
                                          style="font-size: 12px;">{{ $sortDir === 'desc' ? '↓' : '↑' }}</span>
                                @else
                                    <span aria-hidden="true" style="font-size: 12px; opacity: 0.3;">↕</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'item_name', 'direction' => $sortBy === 'item_name' && $sortDir === 'desc' ? 'asc' : 'desc']) }}" 
                               style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 5px;"
                               aria-label="Sort by item name {{ $sortBy === 'item_name' && $sortDir === 'desc' ? 'ascending' : 'descending' }}">
                                Item
                                @if($sortBy === 'item_name')
                                    <span aria-label="{{ $sortDir === 'desc' ? 'sorted descending' : 'sorted ascending' }}" 
                                          style="font-size: 12px;">{{ $sortDir === 'desc' ? '↓' : '↑' }}</span>
                                @else
                                    <span aria-hidden="true" style="font-size: 12px; opacity: 0.3;">↕</span>
                                @endif
                            </a>
                        </th>
                        @if($showStorageUnit)
                        <th scope="col">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'storage_unit_id', 'direction' => $sortBy === 'storage_unit_id' && $sortDir === 'desc' ? 'asc' : 'desc']) }}" 
                               style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 5px;"
                               aria-label="Sort by storage unit {{ $sortBy === 'storage_unit_id' && $sortDir === 'desc' ? 'ascending' : 'descending' }}">
                                Storage Unit
                                @if($sortBy === 'storage_unit_id')
                                    <span aria-label="{{ $sortDir === 'desc' ? 'sorted descending' : 'sorted ascending' }}" 
                                          style="font-size: 12px;">{{ $sortDir === 'desc' ? '↓' : '↑' }}</span>
                                @else
                                    <span aria-hidden="true" style="font-size: 12px; opacity: 0.3;">↕</span>
                                @endif
                            </a>
                        </th>
                        @endif
                        <th scope="col">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'sale_price', 'direction' => $sortBy === 'sale_price' && $sortDir === 'desc' ? 'asc' : 'desc']) }}" 
                               style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 5px;"
                               aria-label="Sort by sale price {{ $sortBy === 'sale_price' && $sortDir === 'desc' ? 'ascending' : 'descending' }}">
                                Sale Price
                                @if($sortBy === 'sale_price')
                                    <span aria-label="{{ $sortDir === 'desc' ? 'sorted descending' : 'sorted ascending' }}" 
                                          style="font-size: 12px;">{{ $sortDir === 'desc' ? '↓' : '↑' }}</span>
                                @else
                                    <span aria-hidden="true" style="font-size: 12px; opacity: 0.3;">↕</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'fees', 'direction' => $sortBy === 'fees' && $sortDir === 'desc' ? 'asc' : 'desc']) }}" 
                               style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 5px;"
                               aria-label="Sort by fees {{ $sortBy === 'fees' && $sortDir === 'desc' ? 'ascending' : 'descending' }}">
                                Fees
                                @if($sortBy === 'fees')
                                    <span aria-label="{{ $sortDir === 'desc' ? 'sorted descending' : 'sorted ascending' }}" 
                                          style="font-size: 12px;">{{ $sortDir === 'desc' ? '↓' : '↑' }}</span>
                                @else
                                    <span aria-hidden="true" style="font-size: 12px; opacity: 0.3;">↕</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'shipping_cost', 'direction' => $sortBy === 'shipping_cost' && $sortDir === 'desc' ? 'asc' : 'desc']) }}" 
                               style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 5px;"
                               aria-label="Sort by shipping cost {{ $sortBy === 'shipping_cost' && $sortDir === 'desc' ? 'ascending' : 'descending' }}">
                                Shipping
                                @if($sortBy === 'shipping_cost')
                                    <span aria-label="{{ $sortDir === 'desc' ? 'sorted descending' : 'sorted ascending' }}" 
                                          style="font-size: 12px;">{{ $sortDir === 'desc' ? '↓' : '↑' }}</span>
                                @else
                                    <span aria-hidden="true" style="font-size: 12px; opacity: 0.3;">↕</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col">Net Sale</th>
                        @if($showPlatform)
                        <th scope="col">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'platform_id', 'direction' => $sortBy === 'platform_id' && $sortDir === 'desc' ? 'asc' : 'desc']) }}" 
                               style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 5px;"
                               aria-label="Sort by platform {{ $sortBy === 'platform_id' && $sortDir === 'desc' ? 'ascending' : 'descending' }}">
                                Platform
                                @if($sortBy === 'platform_id')
                                    <span aria-label="{{ $sortDir === 'desc' ? 'sorted descending' : 'sorted ascending' }}" 
                                          style="font-size: 12px;">{{ $sortDir === 'desc' ? '↓' : '↑' }}</span>
                                @else
                                    <span aria-hidden="true" style="font-size: 12px; opacity: 0.3;">↕</span>
                                @endif
                            </a>
                        </th>
                        @endif
                        <th scope="col">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'user_id', 'direction' => $sortBy === 'user_id' && $sortDir === 'desc' ? 'asc' : 'desc']) }}" 
                               style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 5px;"
                               aria-label="Sort by sold by {{ $sortBy === 'user_id' && $sortDir === 'desc' ? 'ascending' : 'descending' }}">
                                Sold By
                                @if($sortBy === 'user_id')
                                    <span aria-label="{{ $sortDir === 'desc' ? 'sorted descending' : 'sorted ascending' }}" 
                                          style="font-size: 12px;">{{ $sortDir === 'desc' ? '↓' : '↑' }}</span>
                                @else
                                    <span aria-hidden="true" style="font-size: 12px; opacity: 0.3;">↕</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'category_id', 'direction' => $sortBy === 'category_id' && $sortDir === 'desc' ? 'asc' : 'desc']) }}" 
                               style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 5px;"
                               aria-label="Sort by category {{ $sortBy === 'category_id' && $sortDir === 'desc' ? 'ascending' : 'descending' }}">
                                Category
                                @if($sortBy === 'category_id')
                                    <span aria-label="{{ $sortDir === 'desc' ? 'sorted descending' : 'sorted ascending' }}" 
                                          style="font-size: 12px;">{{ $sortDir === 'desc' ? '↓' : '↑' }}</span>
                                @else
                                    <span aria-hidden="true" style="font-size: 12px; opacity: 0.3;">↕</span>
                                @endif
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                        <tr class="{{ !$sale->storage_unit_id ? 'unassigned' : '' }}">
                            <td><time datetime="{{ $sale->sale_date->format('Y-m-d') }}">{{ $sale->sale_date->format('M j, Y') }}</time></td>
                            <td><strong>{{ $sale->item_name }}</strong></td>
                            @if($showStorageUnit)
                            <td>
                                @if($sale->storageUnit)
                                    <a href="{{ route('storage-units.show', $sale->storageUnit) }}" 
                                       class="table-link"
                                       aria-label="View storage unit {{ $sale->storageUnit->name }}">
                                        {{ $sale->storageUnit->name }}
                                    </a>
                                @else
                                    <span class="table-warning" aria-label="Sale not assigned to storage unit">Unassigned</span>
                                @endif
                            </td>
                            @endif
                            <td><span class="currency" aria-label="Sale price {{ number_format($sale->sale_price, 2) }} dollars">${{ number_format($sale->sale_price, 2) }}</span></td>
                            <td><span class="currency" aria-label="Fees {{ number_format($sale->fees, 2) }} dollars">${{ number_format($sale->fees, 2) }}</span></td>
                            <td><span class="currency" aria-label="Shipping cost {{ number_format($sale->shipping_cost, 2) }} dollars">${{ number_format($sale->shipping_cost, 2) }}</span></td>
                            <td class="positive">
                                <strong>
                                    <span class="currency" aria-label="Net sale {{ number_format($sale->net_sale, 2) }} dollars">
                                        ${{ number_format($sale->net_sale, 2) }}
                                    </span>
                                </strong>
                            </td>
                            @if($showPlatform)
                            <td>{{ $sale->platform->name }}</td>
                            @endif
                            <td>{{ $sale->user->name }}</td>
                            <td>{{ $sale->category ? $sale->category->name : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-totals" role="row">
                        <td colspan="{{ $showStorageUnit && $showPlatform ? '6' : ($showStorageUnit || $showPlatform ? '5' : '4') }}" scope="row">
                            <strong>Totals:</strong>
                        </td>
                        <td class="positive">
                            <strong>
                                <span class="currency" aria-label="Total net sales {{ number_format($sales->sum(function($sale) { return $sale->sale_price - $sale->fees - $sale->shipping_cost; }), 2) }} dollars">
                                    ${{ number_format($sales->sum(function($sale) { return $sale->sale_price - $sale->fees - $sale->shipping_cost; }), 2) }}
                                </span>
                            </strong>
                        </td>
                        <td colspan="{{ $showStorageUnit && $showPlatform ? '3' : ($showStorageUnit || $showPlatform ? '3' : '3') }}">
                            <span aria-label="Total of {{ $sales->total() }} items displayed">
                                {{ $sales->total() }} items
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
            
            <!-- Sort Help Text for Screen Readers -->
            <div id="sort-help" class="sr-only">
                Click column headers to sort the table. Currently sorted by {{ str_replace('_', ' ', $sortBy) }} in {{ $sortDir === 'desc' ? 'descending' : 'ascending' }} order.
            </div>
        </div>
        
        <div class="pagination-bottom">
            @include('components.modern-pagination', [
                'paginator' => $sales,
                'showPerPage' => false,
                'perPage' => $perPage
            ])
        </div>
    @else
        <div class="empty-state" role="status" aria-live="polite">
            <h4>No Sales Yet</h4>
            <p>{{ $emptyMessage ?? 'No sales have been recorded yet.' }}</p>
            @if(isset($emptyAction))
                {!! $emptyAction !!}
            @endif
        </div>
    @endif
</div>

<script>
function changePerPage(perPage) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', perPage);
    url.searchParams.delete('page'); // Reset to first page when changing per_page
    window.location.href = url.toString();
}
</script>