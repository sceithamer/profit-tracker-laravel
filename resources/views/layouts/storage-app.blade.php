<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Track profit from storage unit auctions and reselling. Manage sales, calculate ROI, and analyze performance across multiple platforms.')">
    <meta name="keywords" content="storage unit, profit tracker, reselling, ROI calculator, auction tracker">
    <meta name="author" content="Storage Units Profit Tracker">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', 'Storage Units Profit Tracker')">
    <meta property="og:description" content="@yield('meta_description', 'Track profit from storage unit auctions and reselling')">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="@yield('title', 'Storage Units Profit Tracker')">
    <meta name="twitter:description" content="@yield('meta_description', 'Track profit from storage unit auctions and reselling')">
    
    <title>@yield('title', 'Storage Units Profit Tracker')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Skip Navigation Link for Screen Readers -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0 focus:bg-primary focus:text-white focus:p-2 focus:z-50">
        Skip to main content
    </a>

    <header role="banner">
        <nav class="navbar" role="navigation" aria-label="Main navigation">
            <div class="navbar-content">
                <a href="{{ route('dashboard') }}" class="navbar-brand" aria-label="Profit Tracker - Go to Dashboard">
                    <span aria-hidden="true">📊</span> Profit Tracker
                </a>
                
                <div class="navbar-actions">
                    <a href="{{ route('sales.create') }}" class="button button--success" aria-label="Quick Sale Entry - Add a new sale">
                        <span aria-hidden="true">⚡</span> Quick Sale Entry
                    </a>
                    <a href="{{ route('sales.index') }}" class="button" aria-label="View all sales">
                        <span aria-hidden="true">📋</span> All Sales
                    </a>
                    <a href="{{ route('storage-units.index') }}" class="button" aria-label="Manage storage units">
                        <span aria-hidden="true">🏠</span> Storage Units
                    </a>
                    <a href="{{ route('reports.index') }}" class="button" aria-label="View reports and analytics">
                        <span aria-hidden="true">📊</span> Reports
                    </a>
                    
                    <div class="dropdown">
                        <x-button class="dropdown-toggle" 
                                  onclick="toggleDropdown()" 
                                  aria-expanded="false" 
                                  aria-haspopup="true"
                                  aria-label="Settings menu"
                                  id="settings-menu-button">
                            <svg class="cog-icon" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Settings</span>
                        </x-button>
                        <div class="dropdown-menu" 
                             id="settingsDropdown" 
                             role="menu" 
                             aria-labelledby="settings-menu-button">
                            <a href="{{ route('storage-units.create') }}" class="dropdown-item" role="menuitem">
                                <span aria-hidden="true">➕</span> New Storage Unit
                            </a>
                            <a href="{{ route('platforms.index') }}" class="dropdown-item" role="menuitem">
                                <span aria-hidden="true">📱</span> Manage Platforms
                            </a>
                            <a href="{{ route('categories.index') }}" class="dropdown-item" role="menuitem">
                                <span aria-hidden="true">📂</span> Manage Categories
                            </a>
                            <a href="{{ route('users.index') }}" class="dropdown-item" role="menuitem">
                                <span aria-hidden="true">👥</span> User Management
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item" role="menuitem">
                                <span aria-hidden="true">⚙️</span> Profile Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item" role="menuitem" style="border: none; background: none; width: 100%; text-align: left;">
                                    <span aria-hidden="true">🚪</span> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main id="main-content" class="container" role="main">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success" role="alert" aria-live="polite">
                <strong>Success:</strong> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error" role="alert" aria-live="assertive">
                <strong>Error:</strong> {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info" role="alert" aria-live="polite">
                <strong>Info:</strong> {{ session('info') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer role="contentinfo" class="footer">
        <div class="container">
            <p class="text-sm text-muted text-center">
                &copy; {{ date('Y') }} Storage Units Profit Tracker. Built for efficient reselling management.
                @auth
                    <span class="user-info">Logged in as {{ Auth::user()->name }}</span>
                @endauth
            </p>
        </div>
    </footer>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('settingsDropdown');
            const button = document.getElementById('settings-menu-button');
            const isOpen = dropdown.classList.contains('show');
            
            dropdown.classList.toggle('show');
            button.setAttribute('aria-expanded', !isOpen);
            
            // Focus management for accessibility
            if (!isOpen) {
                // Dropdown is being opened, focus first menu item
                const firstMenuItem = dropdown.querySelector('a[role="menuitem"], button[role="menuitem"]');
                if (firstMenuItem) {
                    firstMenuItem.focus();
                }
            }
        }

        // Enhanced keyboard navigation for dropdown
        document.addEventListener('keydown', function(event) {
            const dropdown = document.getElementById('settingsDropdown');
            const button = document.getElementById('settings-menu-button');
            
            if (dropdown.classList.contains('show')) {
                if (event.key === 'Escape') {
                    dropdown.classList.remove('show');
                    button.setAttribute('aria-expanded', 'false');
                    button.focus();
                } else if (event.key === 'ArrowDown') {
                    event.preventDefault();
                    const menuItems = dropdown.querySelectorAll('a[role="menuitem"], button[role="menuitem"]');
                    const currentFocus = document.activeElement;
                    const currentIndex = Array.from(menuItems).indexOf(currentFocus);
                    const nextIndex = (currentIndex + 1) % menuItems.length;
                    menuItems[nextIndex].focus();
                } else if (event.key === 'ArrowUp') {
                    event.preventDefault();
                    const menuItems = dropdown.querySelectorAll('a[role="menuitem"], button[role="menuitem"]');
                    const currentFocus = document.activeElement;
                    const currentIndex = Array.from(menuItems).indexOf(currentFocus);
                    const prevIndex = currentIndex <= 0 ? menuItems.length - 1 : currentIndex - 1;
                    menuItems[prevIndex].focus();
                }
            }
        });

        // Close dropdown when clicking outside
        window.addEventListener('click', function(event) {
            if (!event.target.matches('.dropdown-toggle') && !event.target.closest('.dropdown-toggle')) {
                const dropdown = document.getElementById('settingsDropdown');
                const button = document.getElementById('settings-menu-button');
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                    button.setAttribute('aria-expanded', 'false');
                }
            }
        });
    </script>
</body>
</html>

<style>
.dropdown-divider {
    border-top: 1px solid var(--color-border-light);
    margin: var(--space-sm) 0;
}

.user-info {
    margin-left: var(--space-md);
    font-weight: var(--font-weight-medium);
    color: var(--color-text-primary);
}

/* Alert styles for session messages */
.alert {
    padding: var(--space-md) var(--space-lg);
    margin-bottom: var(--space-lg);
    border-radius: var(--radius-md);
    border: 1px solid transparent;
}

.alert-success {
    background-color: var(--color-success-bg);
    border-color: var(--color-success-border);
    color: var(--color-success-text);
}

.alert-error {
    background-color: var(--color-error-bg);
    border-color: var(--color-error-border);
    color: var(--color-error-text);
}

.alert-info {
    background-color: var(--color-primary-bg);
    border-color: var(--color-primary);
    color: var(--color-primary-text);
}

.cog-icon {
    width: 20px;
    height: 20px;
}
</style>