<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Providers\RouteServiceProvider; // Needed for redirection

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     * Requires a view: resources/views/auth/verify-email.blade.php
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        // If the user"s email is already verified, redirect them to the intended destination or dashboard.
        // Otherwise, show the email verification prompt view.
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(RouteServiceProvider::HOME) // Use constant from RouteServiceProvider
                    : view("auth.verify-email"); // Ensure you have this view
    }
}

