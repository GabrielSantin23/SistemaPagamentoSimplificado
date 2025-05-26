<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Entrar</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        body { font-family: 'Figtree', sans-serif; background-color: #f3f4f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .container { background-color: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); width: 100%; max-width: 28rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; }
        .form-input { display: block; width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; margin-bottom: 1rem; box-sizing: border-box; }
        .form-checkbox { margin-right: 0.5rem; }
        .form-button { background-color: #4f46e5; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; cursor: pointer; }
        .form-link { color: #4f46e5; text-decoration: underline; margin-left: 1rem; }
        .error-message { color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem; }
        .status-message { margin-bottom: 1rem; padding: 1rem; background-color: #d1fae5; color: #065f46; border-radius: 0.375rem; }
    </style>
</head>
<body>
    <div class="container">
        @if (session('status'))
            <div class="status-message">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route("register") }}">
            @csrf

            <div>
                <label for="name" class="form-label">Name</label>
                <input id="name" class="form-input" type="text" name="name" value="{{ old("name") }}" required autofocus autocomplete="name" />
                @error("name")
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-top: 1rem;">
                <label for="cpf_cnpj" class="form-label">CPF/CNPJ</label>
                <input id="cpf_cnpj" class="form-input" type="text" name="cpf_cnpj" value="{{ old("cpf_cnpj") }}" required />
                    @error("cpf_cnpj")
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-top: 1rem;">
                <label for="email" class="form-label">Email</label>
                <input id="email" class="form-input" type="email" name="email" value="{{ old("email") }}" required autocomplete="username" />
                    @error("email")
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-top: 1rem;">
                <label for="user_type" class="form-label">Tipo de Usuário</label>
                <select id="user_type" name="user_type" class="form-input" required>
                    <option value="COMUM" {{ old("user_type") == "COMUM" ? "selected" : "" }}>COMUM</option>
                    <option value="LOJISTA" {{ old("user_type") == "LOJISTA" ? "selected" : "" }}>LOJISTA</option>
                </select>
                    @error("user_type")
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-top: 1rem;">
                <label for="password" class="form-label">Senha</label>
                <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" />
                    @error("password")
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-top: 1rem;">
                <label for="password_confirmation" class="form-label">Confirme sua senha</label>
                <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" />
                    @error("password_confirmation")
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div style="display: flex; align-items: center; justify-content: flex-end; margin-top: 1rem;">
                <a class="form-link" href="{{ route("login") }}">
                    Já possui conta?
                </a>

                <button type="submit" class="form-button" style="margin-left: 1rem;">
                    Registre-se
                </button>
            </div>
        </form>
    </div>
</body>
</html>

