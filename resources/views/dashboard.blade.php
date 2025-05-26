@extends("layouts.app")

@section("title", "Dashboard")

@section("header")
    Sistema de Pagamentos Simplificado
@endsection

@section("content")
    <div class="card-container" style="margin-top: 2rem;">
        @if(Auth::user()->isComum())
            <a href="{{ route("pagamento.index") }}" class="card">
                <h3>Realizar Transferência</h3>
                <p>Envie dinheiro para outros usuários ou lojistas.</p>
            </a>
        @endif

        @if(Auth::user()->isAdmin())
            <a href="{{ route("admin.users.index") }}" class="card">
                <h3>Gerenciar Usuários</h3>
                <p>Visualizar e editar usuários do sistema.</p>
            </a>
        @endif

        <a href="{{ route("profile.edit") }}" class="card">
            <h3>Meu Perfil</h3>
            <p>Atualize suas informações e senha.</p>
        </a>

        <a href="{{ route("pagamento.history") }}" class="card">
            <h3>Histórico de Transações</h3>
            <p>Veja suas transferências enviadas e recebidas.</p>
        </a>
    </div>
@endsection

