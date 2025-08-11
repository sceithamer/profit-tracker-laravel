@extends('layouts.storage-app')

@section('title', $user->name . ' - User Details')

@section('content')

<div class="header-actions">
    <h1>👤 {{ $user->name }}</h1>
    <div style="margin-left: auto;">
        @if(auth()->user()->hasPermission('edit_users'))
            <a href="{{ route('users.edit', $user) }}" class="button">Edit Permissions</a>
        @endif
        <a href="{{ route('users.index') }}" class="button button--secondary">Back to Users</a>
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
        <div class="stat-value {{ $netRevenue < 0 ? 'negative' : ($netRevenue > 0 ? 'positive' : '') }}">
            ${{ number_format($netRevenue, 2) }}
        </div>
        <div class="stat-label">Net Revenue</div>
    </div>
</div>

<div class="card">
    <h2>User Details</h2>
    <div class="user-details">
        <div class="detail-group">
            <div class="detail-item">
                <div class="detail-label">Name</div>
                <div class="detail-value">{{ $user->name }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Email</div>
                <div class="detail-value">{{ $user->email }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Role</div>
                <div class="detail-value">
                    @if($user->is_admin)
                        <span class="badge badge--success">Administrator</span>
                    @else
                        <span class="badge badge--secondary">User</span>
                    @endif
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Account Created</div>
                <div class="detail-value">{{ $user->created_at->format('M j, Y') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <h2>Current Permissions</h2>
    @if($user->is_admin)
        <div class="permissions-notice">
            <p><strong>Administrator Access:</strong> This user has all permissions automatically.</p>
            @if(auth()->user()->hasPermission('edit_users'))
                <a href="{{ route('users.edit', $user) }}" class="button button--small">Manage Permissions</a>
            @endif
        </div>
    @else
        @php
            $groupedPermissions = $user->getGroupedPermissions();
            $hasAnyPermissions = collect($groupedPermissions)->flatten(1)->pluck('granted')->contains(true);
        @endphp
        
        @if($hasAnyPermissions)
            <div class="permissions-display">
                @foreach($groupedPermissions as $category => $permissions)
                    @php
                        $categoryPermissions = collect($permissions)->where('granted', true);
                    @endphp
                    @if($categoryPermissions->count() > 0)
                        <div class="permission-category">
                            <h3 class="category-title">{{ ucwords(str_replace('_', ' ', $category)) }}</h3>
                            <div class="permission-list">
                                @foreach($categoryPermissions as $permission => $details)
                                    <span class="permission-tag">{{ $details['label'] }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="permissions-notice">
                <p>This user currently has no specific permissions assigned.</p>
                @if(auth()->user()->hasPermission('edit_users'))
                    <a href="{{ route('users.edit', $user) }}" class="button button--small button--success">Assign Permissions</a>
                @endif
            </div>
        @endif
    @endif
</div>

@if($recentSales->count() > 0)
    <div class="card">
        <h2>Recent Sales Activity (Last 10)</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Item</th>
                        <th>Sale Price</th>
                        <th>Platform</th>
                        <th>Storage Unit</th>
                        <th>Category</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSales as $sale)
                        <tr>
                            <td>{{ $sale->sale_date->format('M j, Y') }}</td>
                            <td><strong>{{ $sale->item_name }}</strong></td>
                            <td>${{ number_format($sale->sale_price, 2) }}</td>
                            <td>{{ $sale->platform->name }}</td>
                            <td>
                                @if($sale->storageUnit)
                                    {{ $sale->storageUnit->name }}
                                @else
                                    <span class="text-muted">Unassigned</span>
                                @endif
                            </td>
                            <td>
                                @if($sale->category)
                                    {{ $sale->category->name }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p style="margin-top: var(--space-lg);">
            <a href="{{ route('sales.index') }}?user={{ $user->id }}">View all sales by {{ $user->name }}</a>
        </p>
    </div>
@else
    <div class="card">
        <h2>Sales Activity</h2>
        <div class="empty-state">
            <h4>No Sales Yet</h4>
            <p>{{ $user->name }} hasn't recorded any sales yet.</p>
        </div>
    </div>
@endif

@endsection

<style>
.user-details {
    display: grid;
    gap: var(--space-xl);
}

.detail-group {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--space-lg);
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: var(--space-xs);
}

.detail-label {
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-semibold);
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.detail-value {
    font-size: var(--font-size-base);
    color: var(--color-text-primary);
}

.badge {
    display: inline-block;
    padding: var(--space-xs) var(--space-sm);
    border-radius: var(--radius-sm);
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-medium);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.badge--success {
    background: var(--color-success-bg);
    color: var(--color-success-text);
}

.badge--secondary {
    background: var(--color-bg-muted);
    color: var(--color-text-secondary);
}

.permissions-notice {
    text-align: center;
    padding: var(--space-2xl);
    background: var(--color-bg-muted);
    border-radius: var(--radius-md);
}

.permissions-notice p {
    margin-bottom: var(--space-lg);
    color: var(--color-text-secondary);
}

.permissions-display {
    display: grid;
    gap: var(--space-xl);
}

.permission-category {
    border: 1px solid var(--color-border-light);
    border-radius: var(--radius-md);
    padding: var(--space-lg);
}

.category-title {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-semibold);
    color: var(--color-text-primary);
    margin-bottom: var(--space-md);
}

.permission-list {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-sm);
}

.permission-tag {
    display: inline-block;
    padding: var(--space-xs) var(--space-md);
    background: var(--color-primary-bg);
    color: var(--color-primary-text);
    border-radius: var(--radius-sm);
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-medium);
}

.text-muted {
    color: var(--color-text-secondary);
    font-style: italic;
}

.empty-state {
    text-align: center;
    padding: var(--space-3xl) var(--space-xl);
}

.empty-state h4 {
    color: var(--color-text-primary);
    margin-bottom: var(--space-sm);
}

.empty-state p {
    color: var(--color-text-secondary);
}
</style>