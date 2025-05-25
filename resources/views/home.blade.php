@extends("layouts.app")

@section("title", "Home")

@section("header")
    Bem-vindo ao Sistema
@endsection

@section("content")
    <p>Selecione uma das opções abaixo:</p>

    <div class="card-container" style="margin-top: 2rem;">
        @if(Auth::user()->isAdmin())
            <a href="{{ route("admin.users.index") }}" class="card">
                <h3>Cadastro de Usuários</h3>
                <p>Gerenciar usuários do sistema (Admin).</p>
            </a>
        @endif

        @if(Auth::user()->isComum())
             <a href="{{ route("transfer.create") }}" class="card">
                <h3>Pagamento / Transferência</h3>
                <p>Realizar transferência para outros usuários.</p>
            </a>
        @endif

        <a href="{{ route("dashboard") }}" class="card">
            <h3>Dashboard</h3>
            <p>Acessar o painel principal.</p>
        </a>

        <a href="{{ route("profile.edit") }}" class="card">
            <h3>Meu Perfil</h3>
            <p>Visualizar e editar suas informações.</p>
        </a>

    </div>
@endsection

