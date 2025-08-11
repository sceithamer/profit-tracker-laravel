@extends('layouts.storage-app')

@section('title', 'Create New User - User Management')

@section('content')

<div class="page-header">
    <h1>Create New User</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('users.index') }}">User Management</a></li>
            <li aria-current="page">Create User</li>
        </ol>
    </nav>
</div>

<div class="card">
    <h2>User Information</h2>
    
    <form method="POST" action="{{ route('users.store') }}" class="form" novalidate>
        @csrf
        
        <!-- Basic Information -->
        <div class="form-section">
            <h3>Basic Information</h3>
            
            <div class="form-group">
                <label for="name" class="form-label required">Full Name</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       class="form-input @error('name') form-input--error @enderror" 
                       value="{{ old('name') }}" 
                       required 
                       autocomplete="name"
                       aria-describedby="name-help name-error">
                <div id="name-help" class="form-help">
                    Enter the user's full name as it should appear in the system.
                </div>
                @error('name')
                    <div id="name-error" class="form-error" role="alert">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label required">Email Address</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-input @error('email') form-input--error @enderror" 
                       value="{{ old('email') }}" 
                       required 
                       autocomplete="email"
                       aria-describedby="email-help email-error">
                <div id="email-help" class="form-help">
                    This will be used for login and system notifications.
                </div>
                @error('email')
                    <div id="email-error" class="form-error" role="alert">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label required">Password</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="form-input @error('password') form-input--error @enderror" 
                       required 
                       autocomplete="new-password"
                       aria-describedby="password-help password-error">
                <div id="password-help" class="form-help">
                    Must be at least 8 characters long. User can change this after first login.
                </div>
                @error('password')
                    <div id="password-error" class="form-error" role="alert">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label required">Confirm Password</label>
                <input type="password" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       class="form-input @error('password_confirmation') form-input--error @enderror" 
                       required 
                       autocomplete="new-password"
                       aria-describedby="password-confirmation-help">
                <div id="password-confirmation-help" class="form-help">
                    Re-enter the password to confirm it matches.
                </div>
                @error('password_confirmation')
                    <div class="form-error" role="alert">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Administrative Settings -->
        <div class="form-section">
            <h3>Administrative Settings</h3>
            
            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" 
                           id="is_admin" 
                           name="is_admin" 
                           value="1" 
                           class="form-checkbox @error('is_admin') form-checkbox--error @enderror"
                           {{ old('is_admin') ? 'checked' : '' }}
                           aria-describedby="is-admin-help">
                    <label for="is_admin" class="checkbox-label">
                        Administrator Account
                    </label>
                </div>
                <div id="is-admin-help" class="form-help">
                    Administrators have full access to all system features and can manage other users.
                </div>
                @error('is_admin')
                    <div class="form-error" role="alert">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Permissions Section -->
        <div class="form-section" id="permissions-section">
            <h3>User Permissions</h3>
            <p class="form-help">
                Select specific permissions for this user. Administrators automatically have all permissions.
                <span id="admin-notice" style="display: none;" class="text-muted">
                    (Administrator accounts have all permissions automatically)
                </span>
            </p>
            
            @error('permissions')
                <div class="form-error" role="alert">
                    {{ $message }}
                </div>
            @enderror
            
            @foreach($availablePermissions as $resource => $resourcePermissions)
                <div class="permission-group">
                    <h4 class="permission-group-title">{{ ucfirst(str_replace('_', ' ', $resource)) }}</h4>
                    <div class="permission-grid">
                        @foreach($resourcePermissions as $permission => $description)
                            <div class="permission-item">
                                <div class="checkbox-group">
                                    <input type="checkbox" 
                                           id="permission_{{ $permission }}" 
                                           name="permissions[]" 
                                           value="{{ $permission }}" 
                                           class="form-checkbox permission-checkbox"
                                           {{ in_array($permission, old('permissions', [])) ? 'checked' : '' }}
                                           aria-describedby="permission_{{ $permission }}_help">
                                    <label for="permission_{{ $permission }}" class="checkbox-label">
                                        {{ ucfirst(str_replace('_', ' ', explode('_', $permission)[0])) }}
                                        {{ ucfirst(str_replace('_', ' ', $resource)) }}
                                    </label>
                                </div>
                                <div id="permission_{{ $permission }}_help" class="form-help">
                                    {{ $description }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="button button--primary">
                <span aria-hidden="true">👤</span> Create User Account
            </button>
            <a href="{{ route('users.index') }}" class="button button--secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const adminCheckbox = document.getElementById('is_admin');
    const permissionsSection = document.getElementById('permissions-section');
    const adminNotice = document.getElementById('admin-notice');
    const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

    function togglePermissionsSection() {
        if (adminCheckbox.checked) {
            permissionsSection.style.opacity = '0.5';
            adminNotice.style.display = 'inline';
            permissionCheckboxes.forEach(checkbox => {
                checkbox.disabled = true;
                checkbox.setAttribute('aria-disabled', 'true');
            });
        } else {
            permissionsSection.style.opacity = '1';
            adminNotice.style.display = 'none';
            permissionCheckboxes.forEach(checkbox => {
                checkbox.disabled = false;
                checkbox.removeAttribute('aria-disabled');
            });
        }
    }

    // Initial state
    togglePermissionsSection();

    // Listen for admin checkbox changes
    adminCheckbox.addEventListener('change', togglePermissionsSection);

    // Form validation feedback
    const form = document.querySelector('.form');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');

    function validatePasswordMatch() {
        if (confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordInput.setCustomValidity('Passwords do not match');
        } else {
            confirmPasswordInput.setCustomValidity('');
        }
    }

    passwordInput.addEventListener('input', validatePasswordMatch);
    confirmPasswordInput.addEventListener('input', validatePasswordMatch);
});
</script>

<style>
.page-header {
    margin-bottom: var(--space-2xl);
}

.breadcrumb {
    display: flex;
    list-style: none;
    padding: 0;
    margin: var(--space-md) 0 0 0;
    font-size: var(--font-size-sm);
}

.breadcrumb li {
    color: var(--color-text-secondary);
}

.breadcrumb li:not(:last-child)::after {
    content: ' / ';
    margin: 0 var(--space-sm);
}

.breadcrumb a {
    color: var(--color-primary);
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.form-section {
    margin-bottom: var(--space-3xl);
    padding-bottom: var(--space-xl);
    border-bottom: 1px solid var(--color-border-light);
}

.form-section:last-of-type {
    border-bottom: none;
}

.form-section h3 {
    margin: 0 0 var(--space-lg) 0;
    color: var(--color-text-primary);
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-semibold);
}

.form-section h4 {
    margin: 0 0 var(--space-md) 0;
    color: var(--color-text-primary);
    font-size: var(--font-size-base);
    font-weight: var(--font-weight-medium);
}

.permission-group {
    margin-bottom: var(--space-2xl);
}

.permission-group-title {
    margin-bottom: var(--space-md);
    padding-bottom: var(--space-sm);
    border-bottom: 1px solid var(--color-border-light);
    font-weight: var(--font-weight-medium);
    color: var(--color-text-primary);
}

.permission-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--space-lg);
}

.permission-item {
    padding: var(--space-md);
    border: 1px solid var(--color-border-light);
    border-radius: var(--radius-md);
    background-color: var(--color-bg-subtle);
}

.checkbox-group {
    display: flex;
    align-items: flex-start;
    margin-bottom: var(--space-sm);
}

.form-checkbox {
    margin-right: var(--space-sm);
    margin-top: 2px; /* Align with first line of label */
}

.checkbox-label {
    font-weight: var(--font-weight-medium);
    color: var(--color-text-primary);
    cursor: pointer;
}

.form-actions {
    display: flex;
    gap: var(--space-md);
    padding-top: var(--space-xl);
    border-top: 1px solid var(--color-border-light);
}

.required::after {
    content: ' *';
    color: var(--color-danger);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .permission-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .button {
        width: 100%;
        justify-content: center;
    }
}
</style>