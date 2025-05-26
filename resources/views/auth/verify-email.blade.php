<x-guest-layout>
    <div style="margin-bottom: 1rem; font-size: 0.875rem; color: #6b7280;">
        Obrigado por se inscrever! Antes de começar, você poderia verificar seu endereço de e-mail clicando no link que acabamos de enviar? Caso não tenha recebido o e-mail, teremos prazer em enviar outro.
    </div>

    @if (session(\"status\") == \"verification-link-sent\")
        <div class="status-message">
            Um novo link de verificação foi enviado para o endereço de e-mail que você forneceu durante o registro.
        </div>
    @endif

    <div style="margin-top: 1rem; display: flex; align-items: center; justify-content: space-between;">
        <form method="POST" action="{{ route(\"verification.send\") }}">
            @csrf
            <div>
                <button type="submit" class="form-button">
                    Reenviar e-mail de verificação
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route(\"logout\") }}">
            @csrf
            <button type="submit" class="form-link" style="border: none; background: none; cursor: pointer;">
                Sair
            </button>
        </form>
    </div>
</x-guest-layout>

