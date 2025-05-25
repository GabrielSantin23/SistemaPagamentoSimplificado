<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        dd('Middleware role ativo com papel: ', $roles);
        
        if (!Auth::check()) {
            return redirect("login");
        }

        $user = Auth::user();

        foreach ($roles as $role) {
            if (strtoupper($user->user_type) === strtoupper($role)) {
                return $next($request);
            }
        }

        return redirect()->route("dashboard")->with("error", "Você não tem permissão para acessar esta página.");
    }
}
