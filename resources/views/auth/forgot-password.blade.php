<x-guest-layout>
    <div style="margin-bottom: 1rem; font-size: 0.875rem; color: #6b7280;">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
    </div>

    <!-- Session Status -->
    @if (session("status"))
        <div class="status-message">
            {{ session("status") }}
        </div>
    @endif

    <form method="POST" action="{{ route("password.email") }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-input" type="email" name="email" value="{{ old("email") }}" required autofocus />
            @error("email")
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div style="display: flex; align-items: center; justify-content: flex-end; margin-top: 1rem;">
            <button type="submit" class="form-button">
                Email Password Reset Link
            </button>
        </div>
    </form>
</x-guest-layout>

