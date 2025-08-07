# Storage Units Profit Tracker - Development Guide

This document establishes the design principles, architecture decisions, and development standards for the Storage Units Profit Tracker application.

## 🏗️ Project Overview

**Purpose**: Track profit from storage unit auctions and reselling across multiple platforms  
**Tech Stack**: Laravel 12.21.0, PHP 8.3.6, SQLite, Blade templates  
**Target**: Cheap hosting, secure authentication, quick sales workflow (<30 seconds)

## 🎯 Core Development Principles

### **1. Code Reusability & DRY Principle**
> **CRITICAL MANDATE**: "Let's make sure to not duplicate code again and always look for code re-usability in the future"

- **Always** look for opportunities to create reusable components
- **Never** duplicate code across multiple files
- Create shared Blade components for repeated UI patterns
- Extract common functionality into helper methods/services

### **2. No Inline Styles Policy**
- **All styling must use CSS classes** - no inline styles allowed
- Use design tokens from CSS variables system
- Follow SMACSS architecture for CSS organization

### **3. Accessibility First (WCAG 2.1 AA)**
- Proper HTML5 semantic structure required
- ARIA support for complex UI components
- Screen reader compatibility mandatory
- Keyboard navigation support

## 🏛️ Architecture Standards

### **CSS Architecture - SMACSS**

**File Structure:**
```
resources/css/
├── app.css                 # Main entry point with imports
├── base/
│   ├── _variables.css     # Design tokens (100+ CSS custom properties)
│   ├── _reset.css         # CSS reset/normalize
│   └── _typography.css    # Font styles
├── layout/
│   ├── _containers.css    # Grid system, containers
│   └── _navbar.css        # Navigation layout
├── modules/
│   ├── _buttons.css       # Button components
│   ├── _cards.css         # Card components
│   ├── _tables.css        # Table styles
│   ├── _forms.css         # Form components
│   ├── _pagination.css    # Pagination styles
│   └── _alerts.css        # Alert messages
├── state/
│   └── _states.css        # :hover, :focus, .active states
├── theme/
│   └── _themes.css        # Color themes
└── utilities/
    └── _helpers.css       # Utility classes
```

**Design Token System:**
- All colors, spacing, fonts use CSS custom properties
- Format: `var(--color-primary)`, `var(--space-xl)`, `var(--font-size-lg)`
- Dark mode ready with CSS custom property system

### **HTML5 Semantic Structure**

**Required semantic elements:**
```html
<header role="banner">
  <nav role="navigation" aria-label="Main navigation">
    <!-- Skip link for accessibility -->
    <a href="#main-content" class="sr-only">Skip to main content</a>
  </nav>
</header>

<main id="main-content" role="main">
  <h1>Page Title</h1> <!-- Required H1 per page -->
</main>

<footer role="contentinfo">
</footer>
```

### **Heading Hierarchy Rules**

**WCAG 2.1 AA Compliance:**
- Every page MUST have exactly one H1
- No skipped heading levels (H1 → H2 → H3, never H1 → H3)
- Headings describe content structure, not visual appearance
- Components must accept `headingLevel` parameter when containing headings

**Example:**
```php
@include('components.sales-table', [
    'headingLevel' => 'h2',  // Adapts to page context
    // ... other params
])
```

## 🧩 Component Architecture

### **Reusable Components**

**1. Sales Table Component** (`components/sales-table.blade.php`)
- Handles all sales table displays across the application
- Configurable columns, pagination, sorting
- Accessibility compliant with ARIA support
- **Usage:**
```php
@include('components.sales-table', [
    'sales' => $sales,
    'sortBy' => $sortBy,
    'sortDir' => $sortDir,
    'perPage' => $perPage,
    'showStorageUnit' => true,    // optional
    'showPlatform' => true,       // optional  
    'title' => 'Custom Title',    // optional
    'headingLevel' => 'h2'        // optional, defaults to 'h3'
])
```

**2. Modern Pagination Component** (`components/modern-pagination.blade.php`)
- Accessible pagination with ARIA support
- Per-page selection (10, 25, 50, 100)
- Screen reader announcements

### **Component Standards**
- All components must be fully accessible
- Accept configuration parameters for flexibility
- Include comprehensive documentation in component comments
- Use design tokens for all styling
- No inline styles allowed

## 🎨 Design System

### **Color Palette**
```css
:root {
  --color-primary: #3b82f6;      /* Blue */
  --color-success: #22c55e;      /* Green */
  --color-warning: #f59e0b;      /* Yellow */
  --color-danger: #ef4444;       /* Red */
  --color-text-primary: #1f2937;
  --color-text-secondary: #6b7280;
  --color-border-light: #e5e7eb;
}
```

### **Spacing Scale**
```css
--space-xs: 4px;
--space-sm: 8px;
--space-md: 12px;
--space-lg: 16px;
--space-xl: 20px;
--space-2xl: 24px;
--space-3xl: 32px;
--space-4xl: 48px;
--space-6xl: 80px;
```

### **Typography Scale**
```css
--font-size-xs: 12px;
--font-size-sm: 14px;
--font-size-base: 16px;
--font-size-lg: 18px;
--font-size-xl: 20px;
--font-size-2xl: 24px;
--font-size-3xl: 32px;
```

## ♿ Accessibility Requirements

### **WCAG 2.1 AA Standards**

**1. Semantic HTML5**
- Use proper semantic elements (`<header>`, `<nav>`, `<main>`, `<article>`, `<section>`)
- All images must have descriptive alt text
- Form labels properly associated with inputs

**2. ARIA Support**
- Navigation menus with `role="navigation"`
- Dropdown menus with `aria-expanded`, `aria-haspopup`
- Tables with proper scope attributes and captions
- Live regions for dynamic content updates

**3. Keyboard Navigation**
- All interactive elements accessible via keyboard
- Logical tab order maintained
- Skip links for screen readers
- Focus management in dynamic components

**4. Screen Reader Support**
- Descriptive ARIA labels for complex interactions
- Currency values announced properly
- Table sort state communicated
- Form validation errors clearly announced

### **Table Accessibility Checklist**
- [ ] Table caption with description
- [ ] `scope="col"` on all header cells
- [ ] ARIA labels for sort controls
- [ ] Currency values in `<span class="currency">` with ARIA labels
- [ ] Semantic `<time>` elements for dates
- [ ] Sort help text for screen readers

## 🔧 Development Workflow

### **Code Quality Standards**

**1. Laravel Best Practices**
- Use Eloquent relationships properly
- Implement proper request validation
- Follow RESTful routing conventions
- Use resource controllers

**2. Blade Template Standards**
- Extract repeated HTML into components
- Use `@include` for reusable template parts
- Proper escaping with `{{ }}` for user data
- Component documentation in comments

**3. Database Design**
- Use meaningful foreign key relationships
- Implement proper indexing for performance
- Use migrations for all schema changes
- Seed realistic test data

### **Testing & Validation Commands**

**Run these commands before committing:**
```bash
# Lint and type checking (if available)
npm run lint           # JavaScript/CSS linting
npm run typecheck     # TypeScript checking (if applicable)

# Laravel specific
php artisan test      # Run application tests
php artisan migrate   # Ensure migrations work
```

### **Accessibility Testing**
- Manual screen reader testing with NVDA/JAWS
- Automated testing with axe-core browser extension
- Keyboard-only navigation testing
- Color contrast validation

## 📊 Application Features

### **Core Functionality**
- **Storage Unit Management**: Track purchase cost, location, status
- **Sales Tracking**: Record sales with platform, fees, shipping
- **ROI Calculation**: Automatic profit/loss calculation per unit
- **Multi-Platform Support**: eBay, Facebook Marketplace, Mercari, etc.
- **Quick Sale Entry**: <30 second workflow for new sales
- **Unassigned Sales**: Track sales not tied to specific units

### **Performance Requirements**
- **Quick Sale Workflow**: Must complete sale entry in under 30 seconds
- **Responsive Design**: Mobile-friendly interface
- **Efficient Pagination**: Handle large datasets with proper pagination
- **Fast Navigation**: Intuitive menu structure with keyboard shortcuts

## 🔐 Security Standards

**Authentication & Authorization**
- Secure user authentication required
- Session management following Laravel defaults
- CSRF protection on all forms
- Input validation and sanitization

**Data Protection**
- No secrets or keys in repository
- Environment variables for sensitive data
- Proper database connection security
- User data properly escaped in templates

## 🚀 Deployment Guidelines

**Hosting Requirements**
- **Target**: Cheap shared hosting compatibility
- **Database**: SQLite for simplicity and portability
- **PHP**: Version 8.3.6+ required
- **Web Server**: Apache/Nginx with Laravel routing

**Environment Setup**
```bash
# Required PHP extensions
php8.3-sqlite3
php8.3-mbstring
php8.3-xml
php8.3-curl

# Composer dependencies
composer install --optimize-autoloader --no-dev

# Laravel setup
php artisan key:generate
php artisan migrate --seed
```

## 📚 Documentation Standards

### **Component Documentation**
All Blade components must include usage documentation:
```php
{{-- 
Component Name

Purpose: Brief description of component purpose

Usage:
@include('components.component-name', [
    'param1' => $value,      // required - description
    'param2' => true,        // optional - description, defaults to false
])

Accessibility Notes:
- List any specific accessibility features
- ARIA requirements
- Keyboard navigation support
--}}
```

### **CSS Documentation**
```css
/* ==========================================================================
   Module - Component Name
   
   Purpose: Brief description
   Dependencies: List any required base styles
   ========================================================================== */
```

## 🔄 Future Enhancement Guidelines

### **Planned Improvements**
1. **BEM CSS Naming**: Adopt Block-Element-Modifier naming convention
2. **Component Library**: Expand reusable component system
3. **Advanced Analytics**: Enhanced profit reporting and trends
4. **API Development**: REST API for mobile app integration
5. **Performance Optimization**: Query optimization and caching

### **Contribution Guidelines**
- Follow established architecture patterns
- Maintain accessibility standards
- Update this documentation when making architectural changes
- Test across multiple browsers and assistive technologies
- Ensure mobile responsiveness

---

**Last Updated**: August 2025  
**Maintained By**: Development Team  
**WCAG Compliance**: 2.1 AA (90%+ achieved)