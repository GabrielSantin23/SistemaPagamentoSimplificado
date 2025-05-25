<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     * Requires a view: resources/views/auth/reset-password.blade.php
     */
    public function create(Request $request): View
    {
        // Ensure you have a view at resources/views/auth/reset-password.blade.php
        return view("auth.reset-password", ["request" => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "token" => ["required"],
            "email" => ["required", "email"],
            "password" => ["required", "confirmed", Rules\Password::defaults()],
        ]);

        // Attempt to reset the user"s password.
        $status = Password::broker()->reset(
            $request->only("email", "password", "password_confirmation", "token"),
            function ($user, $password) {
                $user->forceFill([
                    "password" => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, redirect to login with a status message.
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route("login")->with("status", __($status));
        }

        // If the password reset failed, throw a validation exception.
        // This typically happens if the token is invalid or expired.
        throw ValidationException::withMessages([
            "email" => [__($status)],
        ]);
    }
}

