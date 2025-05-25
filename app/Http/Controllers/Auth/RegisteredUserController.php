<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rule; // Import Rule for enum validation

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Pass user types to the view if needed for a dropdown,
        // otherwise, it might default to COMUM or be set based on context.
        return view("auth.register");
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "name" => ["required", "string", "max:255"],
            // Added cpf_cnpj validation with uniqueness check
            "cpf_cnpj" => ["required", "string", "max:20", "unique:".User::class],
            "email" => ["required", "string", "lowercase", "email", "max:255", "unique:".User::class],
            // Added user_type validation (ensure it's COMUM or LOJISTA during registration)
            // Assuming ADMIN is created manually or via a separate process
            "user_type" => ["required", Rule::in(["COMUM", "LOJISTA"])],
            "password" => ["required", "confirmed", Rules\Password::defaults()],
        ]);

        $user = User::create([
            "name" => $request->name,
            "cpf_cnpj" => $request->cpf_cnpj,
            "email" => $request->email,
            "user_type" => $request->user_type, // Get user_type from request
            "password" => Hash::make($request->password),
            "balance" => 0.00, // Initialize balance
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on user type or to a default dashboard
        // TODO: Implement specific redirection logic if needed
        return redirect(route("dashboard", absolute: false));
    }
}

