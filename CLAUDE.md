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

### **CSS Architecture - Enhanced SMACSS**

**File Structure:**
```
resources/css/
├── app.css                 # Main entry point with imports
├── base/
│   ├── _variables.css     # Design tokens (125+ CSS custom properties)
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
│   ├── _alerts.css        # Alert messages
│   ├── _auth.css          # Authentication pages & components
│   ├── _dropdowns.css     # Dropdown menus & navigation
│   └── _stats.css         # Dashboard statistics cards
├── state/
│   └── _states.css        # :hover, :focus, .active states
├── theme/
│   └── _themes.css        # Color themes
└── utilities/
    └── _helpers.css       # Utility classes
```

**Enhanced Design Token System:**
- **Colors**: 50+ semantic color tokens with accessibility focus
- **Spacing**: 12-point spacing scale (`--space-xs` to `--space-6xl`)
- **Typography**: Fluid type scale with responsive font sizes
- **Responsive**: Breakpoint variables (`--breakpoint-mobile`, `--breakpoint-tablet`)
- **Containers**: Responsive max-widths (`--container-mobile`, `--container-desktop`)
- **Shadows**: 5-level shadow system for depth hierarchy
- **Format**: `var(--color-primary)`, `var(--space-xl)`, `var(--font-size-lg)`
- **Authentication**: Specialized color tokens for auth pages contrast

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

## 🔐 Authentication & Permissions System

### **Laravel Breeze Integration**
- **Framework**: Laravel Breeze with custom layout preservation
- **Session Security**: 7-day encrypted sessions (`SESSION_LIFETIME=10080`)
- **Authentication Views**: Custom styling integrated with SMACSS architecture
- **Layout Integration**: `storage-app.blade.php` layout maintains design consistency

### **Granular Permissions Model**
**15 Specific Permissions across all resources:**
```php
// Sales Permissions
'create_sales', 'edit_sales', 'delete_sales',

// Storage Units Permissions  
'create_storage_units', 'edit_storage_units', 'delete_storage_units',

// Categories Permissions
'create_categories', 'edit_categories', 'delete_categories',

// Platforms Permissions
'create_platforms', 'edit_platforms', 'delete_platforms',

// User Management Permissions
'create_users', 'edit_users', 'delete_users'
```

### **Permission Architecture**
**Database Structure:**
- **JSON Column**: `permissions` column optimized for SQLite performance
- **Admin Override**: `is_admin` boolean bypasses all permission checks
- **User Model Methods**: `hasPermission()`, `syncPermissions()`, `getAvailablePermissions()`

**Permission Storage Example:**
```json
{
  "permissions": ["create_sales", "edit_sales", "create_storage_units"]
}
```

### **Route Protection System**
**Permission Middleware**: `CheckPermission` middleware protects all routes
```php
// Route Examples
Route::get('/sales/create')->middleware('permission:create_sales');
Route::put('/storage-units/{unit}')->middleware('permission:edit_storage_units');
Route::delete('/users/{user}')->middleware('permission:delete_users');
```

**Middleware Registration:**
```php
// bootstrap/app.php
$middleware->alias([
    'permission' => \App\Http\Middleware\CheckPermission::class,
]);
```

### **User Management System**
- **Admin-Controlled Creation**: No public registration - admins create accounts
- **Permission Assignment**: Granular permission checkbox interface
- **User Roles**: Flexible permission combinations instead of fixed roles
- **Account Verification**: Admin-created accounts auto-verified

### **Authentication Workflow**
1. **Login**: Custom form with responsive design and accessibility
2. **Session**: Long-lived encrypted sessions (7 days)
3. **Permission Check**: Middleware validates permissions per route
4. **Admin Functions**: User creation, permission management
5. **Security**: CSRF protection, input validation, secure redirects

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
- Apply permission middleware to all protected routes
- Use `CheckPermission` middleware for granular access control

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

**4. Permission Development Patterns**
- **Route Protection**: Apply middleware to all CRUD operations
```php
Route::get('/resource/create')->middleware('permission:create_resource');
Route::post('/resource')->middleware('permission:create_resource');
Route::put('/resource/{id}')->middleware('permission:edit_resource');
Route::delete('/resource/{id}')->middleware('permission:delete_resource');
```

- **Controller Validation**: Check permissions in controller methods when needed
```php
public function store(Request $request)
{
    // Permission already validated by middleware
    // Proceed with validation and creation
}
```

- **User Model Methods**: Use built-in permission checking
```php
if ($user->hasPermission('create_sales')) {
    // User can create sales
}

// Sync permissions for user
$user->syncPermissions(['create_sales', 'edit_sales']);
```

- **Migration Patterns**: Always include permission seeding
```php
// In migration or seeder
$user->update(['permissions' => ['create_sales', 'edit_sales']]);
```

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

### **User Management & Security**
- **Admin-Controlled Accounts**: Administrators create user accounts
- **Granular Permissions**: 15 specific permissions across all resources
- **Role-Based Access**: Flexible permission combinations instead of fixed roles
- **Secure Authentication**: Laravel Breeze with 7-day encrypted sessions
- **Permission Enforcement**: Route-level and controller-level protection
- **User Activity Tracking**: Sales attribution and user statistics

### **Performance Requirements**
- **Quick Sale Workflow**: Must complete sale entry in under 30 seconds
- **Responsive Design**: Mobile-friendly interface
- **Efficient Pagination**: Handle large datasets with proper pagination
- **Fast Navigation**: Intuitive menu structure with keyboard shortcuts

## 🔐 Enhanced Security Standards

### **Authentication & Authorization**
**Laravel Breeze Implementation:**
- **Authentication Framework**: Laravel Breeze with custom styling
- **Session Security**: Encrypted sessions with 7-day lifetime
- **Password Security**: Bcrypt hashing with automatic verification
- **Admin-Only Registration**: No public account creation
- **Email Verification**: Auto-verified for admin-created accounts

**Granular Permission System:**
- **Permission Middleware**: Route-level permission enforcement
- **JSON Storage**: Optimized permission storage in SQLite
- **Admin Override**: Boolean flag bypasses permission checks
- **Permission Validation**: Real-time permission checking
- **Secure Redirects**: Permission-denied users redirected safely

**Session Management:**
```php
// .env Configuration
SESSION_LIFETIME=10080        // 7 days in minutes
SESSION_ENCRYPT=true          // Encrypt all session data
SESSION_SECURE_COOKIE=true    // HTTPS-only cookies (production)
SESSION_SAME_SITE=lax        // CSRF protection
```

### **Data Protection & Validation**
**Input Security:**
- **CSRF Protection**: All forms include `@csrf` tokens
- **Input Validation**: Comprehensive validation rules for all user input
- **SQL Injection Prevention**: Eloquent ORM prevents SQL injection
- **XSS Protection**: Automatic escaping with `{{ }}` syntax

**Database Security:**
- **SQLite Optimization**: JSON column indexing for permissions
- **Foreign Key Constraints**: Data integrity enforcement
- **Migration Security**: Version-controlled schema changes
- **Backup Strategy**: Database file backup for SQLite

**Environment Security:**
- **Secret Management**: All sensitive data in `.env` files
- **Key Rotation**: Laravel application key generation
- **No Hardcoded Secrets**: Repository free of credentials
- **Production Security**: Environment-specific configurations

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

## 🧪 Test Account Documentation

### **Authentication Test Accounts**
The application includes pre-configured test accounts with varying permission levels for development and testing:

#### **Administrator Account**
- **Email**: `admin@example.com`
- **Password**: `admin123`
- **Permissions**: Full system access (bypasses all permission checks)
- **Capabilities**: User management, all CRUD operations, system configuration

#### **Sales Manager**
- **Email**: `manager@example.com`
- **Password**: `manager123`
- **Permissions**: 
  - Sales: Create, Edit, Delete
  - Storage Units: Create, Edit
  - Categories: Create, Edit
  - Platforms: Create, Edit
- **Use Case**: Department manager with comprehensive operational access

#### **Data Entry Clerk**
- **Email**: `clerk@example.com`
- **Password**: `clerk123`
- **Permissions**: 
  - Sales: Create, Edit only
- **Use Case**: Limited access user for data entry tasks

#### **Category Manager**
- **Email**: `categories@example.com`
- **Password**: `category123`
- **Permissions**: 
  - Categories: Create, Edit, Delete
  - Platforms: Create, Edit, Delete
- **Use Case**: Specialized role for content management

#### **Read-Only Viewer**
- **Email**: `viewer@example.com`
- **Password**: `viewer123`
- **Permissions**: None (view-only access)
- **Use Case**: Reports and data viewing without modification rights

#### **Basic Test Account**
- **Email**: `test@example.com`
- **Password**: `password`
- **Permissions**: None (legacy test account)
- **Note**: Uses Laravel's default UserFactory password

### **Development Workflow**
**For comprehensive testing**: Use `admin@example.com` with password `admin123`
**For permission testing**: Use role-specific accounts to verify access controls
**For UI testing**: Test with different permission levels to ensure proper interface restrictions

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
**Authentication**: Laravel Breeze with Granular Permissions (15 permissions)  
**WCAG Compliance**: 2.1 AA (95%+ achieved)  
**Security Level**: Production-ready with encrypted sessions