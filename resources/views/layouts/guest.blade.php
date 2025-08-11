<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Login to Storage Units Profit Tracker - Track profit from storage unit auctions and reselling">
        <meta name="author" content="Storage Units Profit Tracker">

        <title>@yield('title', 'Login - Storage Units Profit Tracker')</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="auth-body">
        <!-- Skip Navigation Link for Screen Readers -->
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0 focus:bg-primary focus:text-white focus:p-2 focus:z-50">
            Skip to main content
        </a>

        <div class="auth-container">
            <header class="auth-header" role="banner">
                <a href="{{ route('dashboard') }}" class="auth-logo" aria-label="Storage Units Profit Tracker - Go to Dashboard">
                    <span aria-hidden="true">📊</span>
                    <span class="auth-logo-text">Storage Units<br>Profit Tracker</span>
                </a>
            </header>

            <main id="main-content" class="auth-main" role="main">
                <div class="auth-card">
                    <h1 class="auth-title">Welcome Back</h1>
                    <p class="auth-subtitle">Sign in to your account to continue</p>
                    
                    <!-- Alert Messages -->
                    @if(session('status'))
                        <div class="alert alert-success" role="alert" aria-live="polite">
                            <strong>Success:</strong> {{ session('status') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-error" role="alert" aria-live="assertive">
                            <strong>Error:</strong> {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>

            <footer class="auth-footer" role="contentinfo">
                <p>&copy; {{ date('Y') }} Storage Units Profit Tracker. Secure authentication system.</p>
            </footer>
        </div>
    </body>
</html>
