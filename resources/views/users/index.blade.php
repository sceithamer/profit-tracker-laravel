@extends('layouts.storage-app')

@section('title', 'User Management - Storage Units Profit Tracker')

@section('content')

<div class="header-actions">
    <h1>👥 User Management</h1>
    <div style="margin-left: auto;">
        <a href="{{ route('users.create') }}" class="button button--success">+ Add User</a>
    </div>
</div>

<div class="stats">
    <div class="stat-card">
        <div class="stat-value">{{ $users->total() }}</div>
        <div class="stat-label">Total Users</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $users->where('is_admin', true)->count() }}</div>
        <div class="stat-label">Administrators</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $users->where('is_admin', false)->count() }}</div>
        <div class="stat-label">Regular Users</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $users->sum('sales_count') }}</div>
        <div class="stat-label">Total Sales by Users</div>
    </div>
</div>

<div class="card">
    <h2>All Users ({{ $users->total() }})</h2>
    @if($users->count() > 0)
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Sales Count</th>
                        <th>Permissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->is_admin)
                                    <span class="badge badge--success">Administrator</span>
                                @else
                                    <span class="badge badge--secondary">User</span>
                                @endif
                            </td>
                            <td>{{ number_format($user->sales_count) }} sales</td>
                            <td>
                                @if($user->is_admin)
                                    <span class="permissions-summary">All Permissions</span>
                                @else
                                    @php
                                        $permissionCount = count($user->permissions ?? []);
                                    @endphp
                                    <span class="permissions-summary">
                                        {{ $permissionCount }} permission{{ $permissionCount !== 1 ? 's' : '' }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <a href="{{ route('users.show', $user) }}" class="button button--small">View</a>
                                    <a href="{{ route('users.edit', $user) }}" class="button button--small button--secondary">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $users->links() }}
        </div>
    @else
        <div class="empty-state">
            <h4>No Users Yet</h4>
            <p>No users have been created yet.</p>
            <a href="{{ route('users.create') }}" class="button button--success">+ Create First User</a>
        </div>
    @endif
</div>

@endsection

<style>
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

.permissions-summary {
    font-size: var(--font-size-sm);
    color: var(--color-text-secondary);
}

.pagination-wrapper {
    margin-top: var(--space-xl);
    display: flex;
    justify-content: center;
}

.empty-state {
    text-align: center;
    padding: var(--space-4xl) var(--space-xl);
}

.empty-state h4 {
    color: var(--color-text-primary);
    margin-bottom: var(--space-sm);
}

.empty-state p {
    color: var(--color-text-secondary);
    margin-bottom: var(--space-xl);
}
</style>