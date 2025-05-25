<x-guest-layout>
    <div style="margin-bottom: 1rem; font-size: 0.875rem; color: #6b7280;">
        This is a secure area of the application. Please confirm your password before continuing.
    </div>

    <form method="POST" action="{{ route("password.confirm") }}">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="form-label">Password</label>
            <input id="password" class="form-input" type="password" name="password" required autocomplete="current-password" />
            @error("password")
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 1rem;">
            <button type="submit" class="form-button">
                Confirm
            </button>
        </div>
    </form>
</x-guest-layout>

