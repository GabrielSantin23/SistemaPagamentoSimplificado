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
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view("auth.register");
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "name" => ["required", "string", "max:255"],
            "cpf_cnpj" => ["required", "string", "max:20", "unique:".User::class],
            "email" => ["required", "string", "lowercase", "email", "max:255", "unique:".User::class],
            "user_type" => ["required", Rule::in(["COMUM", "LOJISTA"])],
            "password" => ["required", "confirmed", Rules\Password::defaults()],
        ]);

        $user = User::create([
            "name" => $request->name,
            "cpf_cnpj" => $request->cpf_cnpj,
            "email" => $request->email,
            "user_type" => $request->user_type,
            "password" => Hash::make($request->password),
            "balance" => 0.00,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route("dashboard", absolute: false));
    }
}

