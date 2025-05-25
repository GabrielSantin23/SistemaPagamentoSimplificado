<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     * Requires a view: resources/views/auth/forgot-password.blade.php
     */
    public function create(): View
    {
        // Ensure you have a view at resources/views/auth/forgot-password.blade.php
        return view("auth.forgot-password");
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "email" => ["required", "email"],
        ]);

        // Attempt to send the password reset link to the user.
        $status = Password::broker()->sendResetLink(
            $request->only("email")
        );

        // If the link was successfully sent, redirect back with a status message.
        if ($status == Password::RESET_LINK_SENT) {
            return back()->with("status", __($status));
        }

        // If sending the link failed (e.g., email not found), throw a validation exception.
        throw ValidationException::withMessages([
            "email" => [__($status)],
        ]);
    }
}

