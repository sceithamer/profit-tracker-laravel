{{-- 
User Permissions Form Component

Purpose: Provides an accessible interface for managing user permissions with checkboxes organized by resource category

Usage:
@include('components.user-permissions-form', [
    'user' => $user,                    // required - User model instance
    'userPermissions' => $grouped,      // required - grouped permissions array from $user->getGroupedPermissions()
    'headingLevel' => 'h2'             // optional - heading level for accessibility, defaults to 'h3'
])

Accessibility Notes:
- Grouped fieldsets with proper legends for screen readers
- ARIA descriptions for permission categories
- Screen reader support for permission states
- Keyboard navigation friendly checkboxes
- Admin override functionality with visual feedback
--}}

@php
    $headingLevel = $headingLevel ?? 'h3';
@endphp

<div class="card form-card">
    <{{ $headingLevel }} class="form-section-title">
        User Permissions
    </{{ $headingLevel }}>
    
    <div class="form-group admin-toggle">
        <label class="checkbox-wrapper admin-checkbox">
            <input 
                type="checkbox" 
                name="is_admin" 
                value="1"
                {{ $user->is_admin ? 'checked' : '' }}
                aria-describedby="admin-help"
                id="admin-toggle"
            >
            <span class="checkbox-label">Administrator</span>
        </label>
        <p id="admin-help" class="form-help">
            Administrators have all permissions automatically and don't need individual permissions assigned.
        </p>
    </div>

    <div class="permissions-grid" id="permissions-section" {{ $user->is_admin ? 'data-disabled="true"' : '' }}>
        @foreach($userPermissions as $category => $permissions)
            <fieldset class="permission-category">
                <legend class="permission-category-title">
                    {{ ucwords(str_replace('_', ' ', $category)) }} Permissions
                </legend>
                
                <div class="permission-checkboxes" role="group" aria-labelledby="category-{{ $category }}-title">
                    @foreach($permissions as $permission => $details)
                        <label class="checkbox-wrapper permission-checkbox">
                            <input 
                                type="checkbox" 
                                name="permissions[]" 
                                value="{{ $permission }}"
                                {{ $details['granted'] ? 'checked' : '' }}
                                {{ $user->is_admin ? 'disabled' : '' }}
                                aria-describedby="{{ $permission }}-desc"
                                id="{{ $permission }}-checkbox"
                            >
                            <span class="checkbox-label">{{ $details['label'] }}</span>
                        </label>
                        <div id="{{ $permission }}-desc" class="sr-only">
                            Grant permission to {{ strtolower($details['label']) }} for this user
                        </div>
                    @endforeach
                </div>
            </fieldset>
        @endforeach
    </div>

    @error('permissions')
        <div class="form-error">{{ $message }}</div>
    @enderror
</div>

<script>
// Enhanced permission management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const adminToggle = document.getElementById('admin-toggle');
    const permissionsSection = document.getElementById('permissions-section');
    const permissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
    
    if (!adminToggle || !permissionsSection) return;

    function updatePermissionsState(isAdmin) {
        // Update visual state
        permissionsSection.style.opacity = isAdmin ? '0.6' : '1';
        permissionsSection.style.pointerEvents = isAdmin ? 'none' : 'auto';
        permissionsSection.setAttribute('data-disabled', isAdmin ? 'true' : 'false');
        
        // Update checkbox states
        permissionCheckboxes.forEach(checkbox => {
            checkbox.disabled = isAdmin;
            
            // Update ARIA state
            checkbox.setAttribute('aria-disabled', isAdmin ? 'true' : 'false');
        });

        // Update screen reader announcement
        const announcement = document.getElementById('permissions-announcement');
        if (announcement) {
            announcement.remove();
        }
        
        const newAnnouncement = document.createElement('div');
        newAnnouncement.id = 'permissions-announcement';
        newAnnouncement.className = 'sr-only';
        newAnnouncement.setAttribute('aria-live', 'polite');
        newAnnouncement.textContent = isAdmin 
            ? 'Administrator selected. Individual permissions are now disabled as administrators have all permissions.'
            : 'Individual permissions are now available for selection.';
        
        permissionsSection.appendChild(newAnnouncement);
    }

    // Initialize state
    updatePermissionsState(adminToggle.checked);
    
    // Handle admin toggle changes
    adminToggle.addEventListener('change', function() {
        updatePermissionsState(this.checked);
    });

    // Enhanced keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Tab' && adminToggle.checked) {
            const focusedElement = document.activeElement;
            const isPermissionCheckbox = focusedElement && 
                focusedElement.matches('input[name="permissions[]"]');
            
            if (isPermissionCheckbox) {
                // Skip disabled permission checkboxes when tabbing
                e.preventDefault();
                const nextFocusable = document.querySelector('[tabindex]:not([tabindex="-1"]), button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), a[href]');
                if (nextFocusable) {
                    nextFocusable.focus();
                }
            }
        }
    });

    // Form validation enhancement
    const form = adminToggle.closest('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Ensure admin status is properly handled
            if (adminToggle.checked) {
                // Clear any selected permissions for admins
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
        });
    }
});
</script>

<style>
.permissions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--space-xl);
    margin-top: var(--space-xl);
    transition: opacity var(--transition-fast), pointer-events var(--transition-fast);
}

.permissions-grid[data-disabled="true"] {
    opacity: 0.6;
    pointer-events: none;
}

.permission-category {
    border: 1px solid var(--color-border-light);
    border-radius: var(--radius-md);
    padding: var(--space-lg);
    background: var(--color-bg-primary);
    margin: 0; /* Reset default fieldset margin */
}

.permission-category-title {
    font-weight: var(--font-weight-semibold);
    font-size: var(--font-size-base);
    color: var(--color-text-primary);
    margin-bottom: var(--space-md);
    padding: 0; /* Reset default legend padding */
}

.permission-checkboxes {
    display: flex;
    flex-direction: column;
    gap: var(--space-md);
}

.checkbox-wrapper {
    display: flex;
    align-items: flex-start;
    gap: var(--space-sm);
    cursor: pointer;
    padding: var(--space-sm) 0;
    border-radius: var(--radius-sm);
    transition: background-color var(--transition-fast);
}

.checkbox-wrapper:hover:not([data-disabled="true"]) {
    background-color: var(--color-bg-muted);
    padding-left: var(--space-sm);
    padding-right: var(--space-sm);
}

.checkbox-wrapper input[type="checkbox"] {
    width: 18px;
    height: 18px;
    margin: 0;
    accent-color: var(--color-primary);
    cursor: pointer;
    flex-shrink: 0;
}

.checkbox-wrapper input[type="checkbox"]:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.checkbox-wrapper input[type="checkbox"]:focus {
    outline: 2px solid var(--color-primary);
    outline-offset: 2px;
    border-radius: var(--radius-xs);
}

.checkbox-label {
    font-size: var(--font-size-sm);
    color: var(--color-text-primary);
    user-select: none;
    line-height: 1.4;
    cursor: pointer;
}

.admin-toggle {
    padding: var(--space-lg);
    background: var(--color-warning-bg);
    border: 1px solid var(--color-warning);
    border-radius: var(--radius-md);
    margin-bottom: var(--space-xl);
}

.admin-checkbox .checkbox-label {
    font-weight: var(--font-weight-semibold);
    color: var(--color-warning-text);
}

.form-help {
    font-size: var(--font-size-xs);
    color: var(--color-text-secondary);
    margin-top: var(--space-sm);
    line-height: 1.4;
}

.form-section-title {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-semibold);
    color: var(--color-text-primary);
    margin-bottom: var(--space-lg);
}

/* Screen reader only content */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* Focus states for accessibility */
.permission-category:focus-within {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 1px var(--color-primary);
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .permission-category {
        border-width: 2px;
    }
    
    .checkbox-wrapper input[type="checkbox"]:focus {
        outline-width: 3px;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .permissions-grid {
        transition: none;
    }
    
    .checkbox-wrapper {
        transition: none;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .permissions-grid {
        grid-template-columns: 1fr;
        gap: var(--space-lg);
    }
    
    .permission-category {
        padding: var(--space-md);
    }
    
    .permission-checkboxes {
        gap: var(--space-sm);
    }
}

/* Print styles */
@media print {
    .permissions-grid {
        display: block;
    }
    
    .permission-category {
        page-break-inside: avoid;
        margin-bottom: var(--space-lg);
    }
}
</style>