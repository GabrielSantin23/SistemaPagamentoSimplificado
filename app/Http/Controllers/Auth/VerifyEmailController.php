<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest; // Use the specific request type
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider; // Needed for redirection

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user"s email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Check if the user"s email is already verified.
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME."?verified=1");
        }

        // Mark the user"s email as verified.
        if ($request->user()->markEmailAsVerified()) {
            // Fire the Verified event.
            event(new Verified($request->user()));
        }

        // Redirect the user to their intended destination or dashboard with a verified status.
        return redirect()->intended(RouteServiceProvider::HOME."?verified=1");
    }
}

