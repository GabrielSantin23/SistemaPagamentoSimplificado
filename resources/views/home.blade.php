@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="modules-container">
        <a href="{{ route('users.index') }}" class="module-card">
            Cadastro de usuários
        </a>
        <a href="{{ route('pagamento.index')}}" class="module-card">
            Pagamento
        </a>
    </div>
@endsection