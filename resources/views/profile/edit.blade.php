@extends("layouts.app")

@section("title", "Edit Profile")

@section("header")
    Perfil
@endsection

@section("content")
    <div style="max-width: 3xl; margin: auto; display: grid; grid-template-columns: repeat(1, minmax(0, 1fr)); gap: 1.5rem;">
        <div style="padding: 1rem; background-color: white; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border-radius: 0.375rem;">
            <h3 style="font-size: 1.125rem; font-weight: 500; color: #1f2937; margin-bottom: 1rem;">Informações Pessoais</h3>
            <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1rem;">Atualize as informações do seu perfil e endereço de email.</p>

            <form method="post" action="{{ route("profile.update") }}">
                @csrf
                @method("patch")

                <div>
                    <label for="name" class="form-label">Nome</label>
                    <input id="name" name="name" type="text" class="form-input" value="{{ old("name", $user->name) }}" required autofocus autocomplete="name" />
                    @error("name", "updateProfileInformation")
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-top: 1rem;">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" name="email" type="email" class="form-input" value="{{ old("email", $user->email) }}" required autocomplete="username" />
                     @error("email", "updateProfileInformation")
                        <p class="form-error">{{ $message }}</p>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                            Seu email não foi verificado.
                            <button form="send-verification" class="form-link" style="border: none; background: none; cursor: pointer;">
                                Clique aqui para re-enviar a confirmação.
                            </button>
                        </div>
                        @if (session("status") === "verification-link-sent")
                            <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #10b981;">
                                Um novo link de confirmação foi enviado para seu email.
                            </p>
                        @endif
                    @endif
                </div>

                <div style="display: flex; align-items: center; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="form-button">Salvar</button>
                    @if (session("status") === "profile-updated")
                        <p style="font-size: 0.875rem; color: #6b7280;">Salvo.</p>
                    @endif
                </div>
            </form>
        </div>

        <div style="padding: 1rem; background-color: white; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border-radius: 0.375rem;">
             <h3 style="font-size: 1.125rem; font-weight: 500; color: #1f2937; margin-bottom: 1rem;">Trocar senha</h3>
            <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1rem;">Certifique-se de que sua conta esteja usando uma senha longa e aleatória para permanecer segura.</p>

            <form method="post" action="{{ route("password.update") }}">
                @csrf
                @method("put")

                <div>
                    <label for="current_password" class="form-label">Senha atual</label>
                    <input id="current_password" name="current_password" type="password" class="form-input" autocomplete="current-password" />
                    @error("current_password", "updatePassword")
                         <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-top: 1rem;">
                    <label for="password" class="form-label">Nova Senha</label>
                    <input id="password" name="password" type="password" class="form-input" autocomplete="new-password" />
                    @error("password", "updatePassword")
                         <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-top: 1rem;">
                    <label for="password_confirmation" class="form-label">Confirme sua senha</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-input" autocomplete="new-password" />
                    @error("password_confirmation", "updatePassword")
                         <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                 <div style="display: flex; align-items: center; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="form-button">Salvar</button>
                    @if (session("status") === "password-updated")
                        <p style="font-size: 0.875rem; color: #6b7280;">Salvo.</p>
                    @endif
                </div>
            </form>
        </div>

        <div style="padding: 1rem; background-color: white; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border-radius: 0.375rem;">
            <h3 style="font-size: 1.125rem; font-weight: 500; color: #1f2937; margin-bottom: 1rem;">Deletar conta</h3>
            <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1rem;">Após a exclusão da sua conta, todos os seus recursos e dados serão excluídos permanentemente. Antes de excluir sua conta, baixe todos os dados ou informações que deseja manter.</p>

            <form method="post" action="{{ route("profile.destroy") }}" onsubmit="return confirm('Tem certeza de que deseja excluir sua conta? Esta ação não pode ser desfeita.');">
                @csrf
                @method("delete")

                 <div>
                    <label for="password_delete" class="form-label">Senha</label>
                    <input id="password_delete" name="password" type="password" class="form-input" placeholder="Digite a senha para confirmar a exclusão" autocomplete="current-password" />
                    @error("password", "userDeletion")
                         <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-top: 1.5rem;">
                     <button type="submit" class="form-button form-button-delete">Deletar conta</button>
                </div>
            </form>
        </div>
    </div>

    <form id="send-verification" method="post" action="{{ route("verification.send") }}" style="display: none;">
        @csrf
    </form>
@endsection

