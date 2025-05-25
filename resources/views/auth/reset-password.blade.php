<x-guest-layout>
    <form method="POST" action="{{ route("password.store") }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route("token") }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-input" type="email" name="email" value="{{ old("email", $request->email) }}" required autofocus autocomplete="username" />
            @error("email")
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div style="margin-top: 1rem;">
            <label for="password" class="form-label">Password</label>
            <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" />
            @error("password")
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div style="margin-top: 1rem;">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" />
            @error("password_confirmation")
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div style="display: flex; align-items: center; justify-content: flex-end; margin-top: 1rem;">
            <button type="submit" class="form-button">
                Reset Password
            </button>
        </div>
    </form>
</x-guest-layout>

