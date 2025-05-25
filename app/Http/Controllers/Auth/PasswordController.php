<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     * This method is typically used when the user is already authenticated
     * and wants to change their password from their profile settings.
     */
    public function update(Request $request): RedirectResponse
    {
        // Validate the request data using a specific error bag "updatePassword"
        $validated = $request->validateWithBag("updatePassword", [
            "current_password" => ["required", "current_password"],
            "password" => ["required", Password::defaults(), "confirmed"],
        ]);

        // Update the user's password in the database
        $request->user()->update([
            "password" => Hash::make($validated["password"]),
        ]);

        // Redirect back with a success message
        // Often redirects back to the profile page or the previous page
        return back()->with("status", "password-updated");
    }
}

