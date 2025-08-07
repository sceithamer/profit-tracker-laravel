{{-- 
Button Component

Purpose: Reusable button/link component supporting all button variants and accessibility features

Usage:
<x-button>Default Button</x-button>
<x-button variant="success" type="submit">Submit</x-button>
<x-button href="/path" variant="secondary" size="small">Link Button</x-button>
<x-button variant="danger" onclick="confirm('Delete?')" aria-label="Delete item">Delete</x-button>
<x-button disabled>Disabled</x-button>
<x-button loading>Loading...</x-button>
<x-button variant="outline success" size="large">Outline Success</x-button>
<x-button variant="ghost danger" full-width>Full Width Ghost Danger</x-button>
<x-button variant="primary" size="small" icon-only aria-label="Close">×</x-button>

Parameters:
- variant: 'default', 'success', 'warning', 'secondary', 'danger', 'outline', 'ghost'
           Can combine: 'outline success', 'ghost danger', etc.
- size: 'small', 'default', 'large'
- type: 'button', 'submit', 'reset' (only for <button> elements)
- href: URL for link buttons (renders as <a> instead of <button>)
- disabled: boolean
- loading: boolean (shows loading spinner)
- full-width: boolean (makes button full width)
- icon-only: boolean (square aspect ratio for icon buttons)
- All other attributes (id, class, onclick, aria-*, data-*) are passed through

Accessibility Notes:
- Proper focus management with keyboard navigation
- ARIA labels supported and required for icon-only buttons
- Loading state announced to screen readers
- Semantic HTML based on usage (button vs link)
- Screen reader friendly loading states
--}}

@php
    // Set defaults
    $variant = $variant ?? 'default';
    $size = $size ?? 'default';
    $type = $type ?? 'button';
    $disabled = isset($disabled) && $disabled;
    $loading = isset($loading) && $loading;
    $fullWidth = isset($fullWidth) && $fullWidth;
    $iconOnly = isset($iconOnly) && $iconOnly;
    
    // Build CSS classes array
    $classes = ['button'];
    
    // Handle variant classes (supports multiple variants like "outline success")
    if ($variant !== 'default') {
        $variantParts = explode(' ', trim($variant));
        foreach ($variantParts as $variantPart) {
            $variantPart = trim($variantPart);
            if ($variantPart) {
                $classes[] = 'button--' . $variantPart;
            }
        }
    }
    
    // Add size class
    if ($size !== 'default') {
        $classes[] = 'button--' . $size;
    }
    
    // Add utility classes
    if ($fullWidth) {
        $classes[] = 'button--full-width';
    }
    
    if ($iconOnly) {
        $classes[] = 'button--icon-only';
    }
    
    // Add state classes
    if ($disabled) {
        $classes[] = 'button--disabled';
    }
    
    if ($loading) {
        $classes[] = 'button--loading';
    }
    
    // Merge with any additional classes passed
    if (isset($attributes['class'])) {
        $classes[] = $attributes['class'];
    }
    
    // Remove duplicates and create class string
    $classes = array_unique($classes);
    $classString = implode(' ', $classes);
    
    // Determine if this should be a link or button
    $isLink = isset($href);
    
    // Prepare attributes - exclude component parameters from HTML attributes
    $excludedAttributes = ['class', 'variant', 'size', 'loading', 'fullWidth', 'iconOnly'];
    $elementAttributes = $attributes->except($excludedAttributes);
    
    if ($isLink) {
        // For links, remove button-specific attributes
        $elementAttributes = $elementAttributes->except(['type', 'disabled']);
        
        // Add href
        $elementAttributes = $elementAttributes->merge(['href' => $href]);
        
        // Handle disabled state for links
        if ($disabled) {
            $elementAttributes = $elementAttributes->merge([
                'aria-disabled' => 'true',
                'tabindex' => '-1'
            ]);
        }
    } else {
        // For buttons, set type and disabled
        $elementAttributes = $elementAttributes->merge(['type' => $type]);
        
        if ($disabled || $loading) {
            $elementAttributes = $elementAttributes->merge(['disabled' => true]);
        }
    }
    
    // Add loading aria-label if loading
    if ($loading && !$elementAttributes->has('aria-label')) {
        $elementAttributes = $elementAttributes->merge(['aria-label' => 'Loading, please wait']);
    }
    
    // Accessibility validation for icon-only buttons
    if ($iconOnly && !$elementAttributes->has('aria-label') && !$elementAttributes->has('aria-labelledby')) {
        // In development, we could log a warning here
        // For now, we'll ensure there's at least a basic label
        $elementAttributes = $elementAttributes->merge(['aria-label' => 'Button']);
    }
@endphp

@if($isLink)
    <a {{ $elementAttributes->merge(['class' => $classString]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $elementAttributes->merge(['class' => $classString]) }}>
        {{ $slot }}
    </button>
@endif