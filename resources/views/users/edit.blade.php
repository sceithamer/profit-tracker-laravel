@extends('layouts.storage-app')

@section('title', 'Edit ' . $user->name . ' - User Permissions')

@section('content')

<div class="header-actions">
    <h1>✏️ Edit {{ $user->name }}</h1>
    <div style="margin-left: auto;">
        <a href="{{ route('users.show', $user) }}" class="button button--secondary">Cancel</a>
    </div>
</div>

<form method="POST" action="{{ route('users.update', $user) }}" class="form-container">
    @csrf
    @method('PUT')
    
    <div class="card form-card">
        <h2>Basic Information</h2>
        
        <div class="form-group">
            <label for="name">Name <span class="required">*</span></label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="{{ old('name', $user->name) }}" 
                required
                class="form-control @error('name') form-control--error @enderror"
            >
            @error('name')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email Address <span class="required">*</span></label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="{{ old('email', $user->email) }}" 
                required
                class="form-control @error('email') form-control--error @enderror"
            >
            @error('email')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>
    </div>

    @include('components.user-permissions-form', [
        'user' => $user,
        'userPermissions' => $userPermissions,
        'headingLevel' => 'h2'
    ])

    <div class="form-actions">
        <button type="submit" class="button button--success">Update User Permissions</button>
        <a href="{{ route('users.show', $user) }}" class="button button--secondary">Cancel</a>
    </div>
</form>

@endsection

<style>
.form-container {
    max-width: none;
}

.form-actions {
    display: flex;
    gap: var(--space-md);
    justify-content: flex-end;
    padding-top: var(--space-xl);
    border-top: 1px solid var(--color-border-light);
}

.required {
    color: var(--color-danger);
}

.form-control {
    width: 100%;
    padding: var(--space-md);
    border: 1px solid var(--color-border-light);
    border-radius: var(--radius-md);
    font-size: var(--font-size-base);
    transition: border-color var(--transition-fast);
}

.form-control:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 2px var(--color-primary-bg);
}

.form-control--error {
    border-color: var(--color-danger);
}

.form-control--error:focus {
    border-color: var(--color-danger);
    box-shadow: 0 0 0 2px var(--color-danger-bg);
}

.form-error {
    color: var(--color-danger);
    font-size: var(--font-size-sm);
    margin-top: var(--space-xs);
}

.form-group {
    margin-bottom: var(--space-lg);
}

.form-group label {
    display: block;
    font-weight: var(--font-weight-semibold);
    color: var(--color-text-primary);
    margin-bottom: var(--space-sm);
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }
}
</style>