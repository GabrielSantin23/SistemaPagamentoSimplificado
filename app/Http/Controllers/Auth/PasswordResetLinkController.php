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
    public function create(): View
    {
        return view("auth.forgot-password");
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "email" => ["required", "email"],
        ]);

        $status = Password::broker()->sendResetLink(
            $request->only("email")
        );

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with("status", __($status));
        }

        throw ValidationException::withMessages([
            "email" => [__($status)],
        ]);
    }
}

