@extends('layouts.guest')

@section('title', 'Login - Storage Units Profit Tracker')

@section('content')
<form method="POST" action="{{ route('login') }}" class="auth-form" novalidate>
    @csrf

    <!-- Email Address -->
    <div class="form-group">
        <label for="email" class="form-label required">Email Address</label>
        <input type="email" 
               id="email" 
               name="email" 
               class="form-input @error('email') form-input--error @enderror" 
               value="{{ old('email') }}" 
               required 
               autofocus 
               autocomplete="username"
               aria-describedby="email-error"
               placeholder="Enter your email address">
        @error('email')
            <div id="email-error" class="form-error" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Password -->
    <div class="form-group">
        <label for="password" class="form-label required">Password</label>
        <input type="password" 
               id="password" 
               name="password" 
               class="form-input @error('password') form-input--error @enderror" 
               required 
               autocomplete="current-password"
               aria-describedby="password-error"
               placeholder="Enter your password">
        @error('password')
            <div id="password-error" class="form-error" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Remember Me -->
    <div class="form-group">
        <div class="checkbox-group">
            <input type="checkbox" 
                   id="remember_me" 
                   name="remember" 
                   class="form-checkbox"
                   {{ old('remember') ? 'checked' : '' }}>
            <label for="remember_me" class="checkbox-label">
                Remember me for 7 days
            </label>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="auth-form-actions">
        <button type="submit" class="button button--primary button--full-width">
            <span aria-hidden="true">🔐</span> Sign In
        </button>
        
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="auth-link">
                Forgot your password?
            </a>
        @endif
    </div>
</form>
@endsection
