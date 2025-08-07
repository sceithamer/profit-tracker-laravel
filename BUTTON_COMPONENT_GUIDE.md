# Button Component Usage Guide

## Overview
The `<x-button>` component provides a unified, accessible, and reusable way to create buttons and button-styled links throughout the application.

## Basic Usage

### Simple Buttons
```blade
{{-- Default button --}}
<x-button>Click Me</x-button>

{{-- Submit button --}}
<x-button type="submit">Save Changes</x-button>

{{-- Link button --}}
<x-button href="/dashboard">Go to Dashboard</x-button>
```

### Button Variants
```blade
{{-- Primary (default) --}}
<x-button>Primary Button</x-button>

{{-- Success (green) --}}
<x-button variant="success">Success Button</x-button>

{{-- Warning (yellow) --}}
<x-button variant="warning">Warning Button</x-button>

{{-- Secondary (gray) --}}
<x-button variant="secondary">Secondary Button</x-button>

{{-- Danger (red) --}}
<x-button variant="danger">Delete Item</x-button>

{{-- Outline style --}}
<x-button variant="outline">Outline Button</x-button>

{{-- Ghost style (transparent) --}}
<x-button variant="ghost">Ghost Button</x-button>
```

### Button Sizes
```blade
{{-- Small button --}}
<x-button size="small">Small</x-button>

{{-- Default size --}}
<x-button>Default Size</x-button>

{{-- Large button --}}
<x-button size="large">Large Button</x-button>
```

### Button States
```blade
{{-- Disabled button --}}
<x-button disabled>Disabled Button</x-button>

{{-- Loading button --}}
<x-button loading>Loading...</x-button>

{{-- Disabled link --}}
<x-button href="/path" disabled>Disabled Link</x-button>
```

## Advanced Usage

### Custom Attributes
```blade
{{-- JavaScript events --}}
<x-button onclick="alert('Clicked!')">Alert Button</x-button>

{{-- CSS classes --}}
<x-button class="my-custom-class">Custom Styled</x-button>

{{-- Accessibility labels --}}
<x-button aria-label="Delete user account" variant="danger">Delete</x-button>

{{-- Data attributes --}}
<x-button data-user-id="123" data-action="delete">Delete User</x-button>

{{-- Form attributes --}}
<x-button type="submit" form="my-form">Submit External Form</x-button>
```

### Complex Examples
```blade
{{-- Confirmation button with JavaScript --}}
<x-button 
    variant="danger" 
    onclick="return confirm('Are you sure you want to delete this item?')"
    aria-label="Delete storage unit permanently">
    Delete Unit
</x-button>

{{-- Link with query parameters --}}
<x-button 
    href="{{ route('sales.create', ['unit' => $unit->id]) }}" 
    variant="success" 
    size="small">
    + Add Sale
</x-button>

{{-- Loading state with custom text --}}
<x-button 
    type="submit" 
    variant="success" 
    loading 
    aria-label="Saving your changes, please wait">
    Saving...
</x-button>
```

## Migration Strategy

### Before (Old Code)
```blade
{{-- Old button HTML --}}
<button type="submit" class="button button--success">Update Storage Unit</button>
<a href="{{ route('dashboard') }}" class="button button--secondary">Cancel</a>

{{-- Small button --}}
<a href="{{ route('storage-units.show', $unit) }}" class="button button--small">View</a>

{{-- Danger button with onclick --}}
<button 
    type="submit" 
    class="button button--danger"
    onclick="return confirm('Delete this item?')">
    Delete
</button>
```

### After (Component Usage)
```blade
{{-- New component usage --}}
<x-button type="submit" variant="success">Update Storage Unit</x-button>
<x-button href="{{ route('dashboard') }}" variant="secondary">Cancel</x-button>

{{-- Small button --}}
<x-button href="{{ route('storage-units.show', $unit) }}" size="small">View</x-button>

{{-- Danger button with onclick --}}
<x-button 
    type="submit" 
    variant="danger"
    onclick="return confirm('Delete this item?')">
    Delete
</x-button>
```

## Common Patterns

### Form Buttons
```blade
{{-- Save/Cancel pattern --}}
<div class="form-actions">
    <x-button type="submit" variant="success">Save Changes</x-button>
    <x-button href="{{ url()->previous() }}" variant="secondary">Cancel</x-button>
</div>
```

### CRUD Actions
```blade
{{-- View/Edit/Delete pattern --}}
<x-button href="{{ route('items.show', $item) }}" size="small">View</x-button>
<x-button href="{{ route('items.edit', $item) }}" size="small" variant="secondary">Edit</x-button>
<x-button 
    type="submit" 
    variant="danger" 
    size="small"
    onclick="return confirm('Delete this item?')"
    form="delete-form-{{ $item->id }}">
    Delete
</x-button>
```

### Navigation Actions
```blade
{{-- Add new item --}}
<x-button href="{{ route('items.create') }}" variant="success">+ Add New Item</x-button>

{{-- Back button --}}
<x-button href="{{ route('items.index') }}" variant="ghost">← Back to List</x-button>
```

## Accessibility Features

### Built-in Accessibility
- **Keyboard Navigation**: Full keyboard support
- **Screen Reader Support**: Proper ARIA labels and announcements
- **Focus Management**: Visible focus indicators
- **Loading States**: Announced to assistive technology
- **Disabled States**: Properly communicated to screen readers

### Custom Accessibility
```blade
{{-- Custom ARIA labels --}}
<x-button 
    variant="danger" 
    aria-label="Permanently delete storage unit {{ $unit->name }}"
    aria-describedby="delete-warning">
    Delete
</x-button>
<div id="delete-warning" class="sr-only">
    This action cannot be undone
</div>
```

## CSS Classes Generated

The component automatically generates CSS classes based on parameters:

- Base: `button`
- Variants: `button--success`, `button--danger`, etc.
- Sizes: `button--small`, `button--large`
- States: `button--disabled`, `button--loading`

## Browser Support

Works with all modern browsers and gracefully degrades for older browsers.

## Performance Notes

- Components are compiled at build time (no runtime overhead)
- CSS classes are reused from existing stylesheet
- No JavaScript required for basic functionality

## Troubleshooting

### Common Issues

1. **Component not found**: Ensure you're using `<x-button>` not `<x:button>`
2. **Styles not applied**: Check that `resources/css/modules/_buttons.css` is included
3. **Link not working**: Ensure `href` parameter is provided for link buttons
4. **Form submission issues**: Use `type="submit"` for form submission buttons

### Debug Tips

```blade
{{-- Check what attributes are passed --}}
<x-button {{ $attributes->merge(['debug' => 'true']) }}>Debug Button</x-button>
```