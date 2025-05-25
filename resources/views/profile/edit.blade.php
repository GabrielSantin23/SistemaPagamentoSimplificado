@extends("layouts.app")

@section("title", "Edit Profile")

@section("header")
    Profile
@endsection

@section("content")
    <div style="max-width: 3xl; margin: auto; display: grid; grid-template-columns: repeat(1, minmax(0, 1fr)); gap: 1.5rem;">
        {{-- Update Profile Information Form --}}
        <div style="padding: 1rem; background-color: white; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border-radius: 0.375rem;">
            <h3 style="font-size: 1.125rem; font-weight: 500; color: #1f2937; margin-bottom: 1rem;">Profile Information</h3>
            <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1rem;">Update your account"s profile information and email address.</p>

            <form method="post" action="{{ route("profile.update") }}">
                @csrf
                @method("patch")

                <div>
                    <label for="name" class="form-label">Name</label>
                    <input id="name" name="name" type="text" class="form-input" value="{{ old("name", $user->name) }}" required autofocus autocomplete="name" />
                    @error("name", "updateProfileInformation") {{-- Assuming error bag --}}
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-top: 1rem;">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" name="email" type="email" class="form-input" value="{{ old("email", $user->email) }}" required autocomplete="username" />
                     @error("email", "updateProfileInformation")
                        <p class="form-error">{{ $message }}</p>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                            Your email address is unverified.
                            <button form="send-verification" class="form-link" style="border: none; background: none; cursor: pointer;">
                                Click here to re-send the verification email.
                            </button>
                        </div>
                        @if (session("status") === "verification-link-sent")
                            <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #10b981;">
                                A new verification link has been sent to your email address.
                            </p>
                        @endif
                    @endif
                </div>

                <div style="display: flex; align-items: center; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="form-button">Save</button>
                    @if (session("status") === "profile-updated")
                        <p style="font-size: 0.875rem; color: #6b7280;">Saved.</p>
                    @endif
                </div>
            </form>
        </div>

        {{-- Update Password Form --}}
        <div style="padding: 1rem; background-color: white; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border-radius: 0.375rem;">
             <h3 style="font-size: 1.125rem; font-weight: 500; color: #1f2937; margin-bottom: 1rem;">Update Password</h3>
            <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1rem;">Ensure your account is using a long, random password to stay secure.</p>

            <form method="post" action="{{ route("password.update") }}">
                @csrf
                @method("put")

                <div>
                    <label for="current_password" class="form-label">Current Password</label>
                    <input id="current_password" name="current_password" type="password" class="form-input" autocomplete="current-password" />
                    @error("current_password", "updatePassword")
                         <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-top: 1rem;">
                    <label for="password" class="form-label">New Password</label>
                    <input id="password" name="password" type="password" class="form-input" autocomplete="new-password" />
                    @error("password", "updatePassword")
                         <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-top: 1rem;">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-input" autocomplete="new-password" />
                    @error("password_confirmation", "updatePassword")
                         <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                 <div style="display: flex; align-items: center; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="form-button">Save</button>
                    @if (session("status") === "password-updated")
                        <p style="font-size: 0.875rem; color: #6b7280;">Saved.</p>
                    @endif
                </div>
            </form>
        </div>

        {{-- Delete Account Form --}}
        <div style="padding: 1rem; background-color: white; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border-radius: 0.375rem;">
            <h3 style="font-size: 1.125rem; font-weight: 500; color: #1f2937; margin-bottom: 1rem;">Delete Account</h3>
            <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1rem;">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>

            {{-- Basic confirmation, real implementation might use a modal --}}
            <form method="post" action="{{ route("profile.destroy") }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                @csrf
                @method("delete")

                 <div>
                    <label for="password_delete" class="form-label">Password</label>
                    <input id="password_delete" name="password" type="password" class="form-input" placeholder="Enter password to confirm deletion" autocomplete="current-password" />
                    @error("password", "userDeletion")
                         <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-top: 1.5rem;">
                     <button type="submit" class="form-button form-button-delete">Delete Account</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Separate form for sending verification email --}}
    <form id="send-verification" method="post" action="{{ route("verification.send") }}" style="display: none;">
        @csrf
    </form>
@endsection

