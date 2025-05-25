<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider; // Needed for redirection

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        // If the user"s email is already verified, redirect them.
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        // Send the email verification notification.
        $request->user()->sendEmailVerificationNotification();

        // Redirect back with a status message indicating the notification was sent.
        return back()->with("status", "verification-link-sent");
    }
}

