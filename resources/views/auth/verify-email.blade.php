<x-guest-layout>
    <div style="margin-bottom: 1rem; font-size: 0.875rem; color: #6b7280;">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\"t receive the email, we will gladly send you another.
    </div>

    @if (session(\"status\") == \"verification-link-sent\")
        <div class="status-message">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <div style="margin-top: 1rem; display: flex; align-items: center; justify-content: space-between;">
        <form method="POST" action="{{ route(\"verification.send\") }}">
            @csrf
            <div>
                <button type="submit" class="form-button">
                    Resend Verification Email
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route(\"logout\") }}">
            @csrf
            <button type="submit" class="form-link" style="border: none; background: none; cursor: pointer;">
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>

