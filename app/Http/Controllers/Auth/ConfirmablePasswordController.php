<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the password confirmation view.
     * Requires a view: resources/views/auth/confirm-password.blade.php
     */
    public function show(): View
    {
        // Ensure you have a view at resources/views/auth/confirm-password.blade.php
        return view("auth.confirm-password");
    }

    /**
     * Confirm the user"s password.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the provided password against the authenticated user"s password
        if (! Auth::guard("web")->validate([
            "email" => $request->user()->email,
            "password" => $request->password,
        ])) {
            throw ValidationException::withMessages([
                "password" => __("auth.password"), // Use Laravel"s built-in translation
            ]);
        }

        // Set a timestamp in the session indicating the password was confirmed.
        // This is used by the `password.confirm` middleware.
        $request->session()->put("auth.password_confirmed_at", time());

        // Redirect the user to their intended destination or a default page.
        return redirect()->intended(route("dashboard", absolute: false));
    }
}

