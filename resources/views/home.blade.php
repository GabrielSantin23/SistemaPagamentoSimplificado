@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="modules-container">
        <a href="{{ route('cadastro.index') }}" class="module-card">
            Cadastro
        </a>
        <a href="{{ route('pagamento.index') }}" class="module-card">
            Pagamento
        </a>
    </div>
@endsection
