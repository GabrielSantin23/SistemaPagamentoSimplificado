<x-guest-layout>
    <form method="POST" action="{{ route("register") }}">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="form-label">Name</label>
            <input id="name" class="form-input" type="text" name="name" value="{{ old("name") }}" required autofocus autocomplete="name" />
            @error("name")
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- CPF/CNPJ -->
        <div style="margin-top: 1rem;">
            <label for="cpf_cnpj" class="form-label">CPF/CNPJ</label>
            <input id="cpf_cnpj" class="form-input" type="text" name="cpf_cnpj" value="{{ old("cpf_cnpj") }}" required />
             @error("cpf_cnpj")
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div style="margin-top: 1rem;">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-input" type="email" name="email" value="{{ old("email") }}" required autocomplete="username" />
             @error("email")
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- User Type -->
        <div style="margin-top: 1rem;">
            <label for="user_type" class="form-label">Tipo de Usuário</label>
            <select id="user_type" name="user_type" class="form-input" required>
                <option value="COMUM" {{ old("user_type") == "COMUM" ? "selected" : "" }}>Comum</option>
                <option value="LOJISTA" {{ old("user_type") == "LOJISTA" ? "selected" : "" }}>Lojista</option>
            </select>
             @error("user_type")
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div style="margin-top: 1rem;">
            <label for="password" class="form-label">Password</label>
            <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" />
             @error("password")
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div style="margin-top: 1rem;">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" />
             @error("password_confirmation")
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div style="display: flex; align-items: center; justify-content: flex-end; margin-top: 1rem;">
            <a class="form-link" href="{{ route("login") }}">
                Already registered?
            </a>

            <button type="submit" class="form-button" style="margin-left: 1rem;">
                Register
            </button>
        </div>
    </form>
</x-guest-layout>

