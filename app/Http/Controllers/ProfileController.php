<?php

namespace App\Http\Controllers;

// Note: ProfileUpdateRequest would typically come from Breeze/Jetstream.
// If you don't have it, you might need to create it or use the basic Request.
// use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request; // Using basic Request for now
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule; // For email uniqueness validation
use Illuminate\Support\Facades\Hash; // For password validation

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     * Requires a view: resources/views/profile/edit.blade.php
     */
    public function edit(Request $request): View
    {
        // Ensure you have a view at resources/views/profile/edit.blade.php
        // This view would typically be provided by Breeze/Jetstream.
        return view("profile.edit", [
            "user" => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     * Placeholder implementation.
     */
    public function update(Request $request): RedirectResponse // Using basic Request
    {
        // Validate the request data
        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            // Ensure email is unique, ignoring the current user's email
            "email" => ["required", "string", "lowercase", "email", "max:255", Rule::unique("users")->ignore($request->user()->id)],
            // Add validation for other fields you allow updating here
        ]);

        // Fill the user model with validated data
        // Make sure 'name' and 'email' are in the $fillable array in your User model
        $request->user()->fill($validated);

        // If the email address was changed, reset the email verification status
        if ($request->user()->isDirty("email")) {
            $request->user()->email_verified_at = null;
        }

        // Save the changes
        $request->user()->save();

        // Redirect back to the profile edit page with a success status
        return Redirect::route("profile.edit")->with("status", "profile-updated");
    }

    /**
     * Delete the user's account.
     * Placeholder implementation.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validate that the provided password matches the user's current password
        $request->validateWithBag("userDeletion", [
            "password" => ["required", "current_password"],
        ]);

        $user = $request->user();

        // Log the user out
        Auth::logout();

        // Delete the user record
        $user->delete();

        // Invalidate the session and regenerate the token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the homepage
        return Redirect::to("/");
    }
}

