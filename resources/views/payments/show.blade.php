@extends('layouts.app')

@section('title', 'Detalhes da Transação')

@section('header', 'Detalhes da Transação')

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 bg-primary-700 text-white">
        <h3 class="text-lg leading-6 font-medium">Detalhes da Transferência</h3>
        <p class="mt-1 max-w-2xl text-sm text-primary-100">Informações completas sobre esta transação.</p>
    </div>
    
    <div class="border-t border-gray-200 px-4 py-5 sm:px-6 
        @if($transaction->status === 'completed') bg-green-50
        @elseif($transaction->status === 'failed') bg-red-50
        @elseif($transaction->status === 'reversed') bg-yellow-50
        @else bg-gray-50 @endif">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-lg font-medium 
                    @if($transaction->status === 'completed') text-green-800
                    @elseif($transaction->status === 'failed') text-red-800
                    @elseif($transaction->status === 'reversed') text-yellow-800
                    @else text-gray-800 @endif">
                    @if($transaction->status === 'completed') Transação Concluída
                    @elseif($transaction->status === 'failed') Transação Falhou
                    @elseif($transaction->status === 'reversed') Transação Revertida
                    @else Transação Pendente @endif
                </h4>
                <p class="mt-1 text-sm text-gray-600">
                    @if($transaction->status === 'completed') 
                        A transferência foi processada com sucesso.
                    @elseif($transaction->status === 'failed') 
                        A transferência não pôde ser concluída.
                    @elseif($transaction->status === 'reversed') 
                        A transferência foi revertida e o valor retornou para o remetente.
                    @else 
                        A transferência está sendo processada.
                    @endif
                </p>
            </div>
            <div>
                <span class="px-4 py-2 inline-flex text-md font-semibold rounded-full 
                    @if($transaction->status === 'completed') bg-green-100 text-green-800
                    @elseif($transaction->status === 'failed') bg-red-100 text-red-800
                    @elseif($transaction->status === 'reversed') bg-yellow-100 text-yellow-800
                    @else bg-gray-100 text-gray-800 @endif">
                    @if($transaction->payer_id === Auth::id())
                        - R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                    @else
                        + R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                    @endif
                </span>
            </div>
        </div>
    </div>
    
    <div class="border-t border-gray-200">
        <dl>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">ID da Transação</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $transaction->id }}</dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Data e Hora</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Remetente</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $transaction->payer->full_name ?? $transaction->payer->name }}
                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                        {{ $transaction->payer->cpf_cnpj }}
                    </span>
                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $transaction->payer->isShopkeeper() ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                        {{ $transaction->payer->isShopkeeper() ? 'Lojista' : 'Usuário Comum' }}
                    </span>
                </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Destinatário</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $transaction->payee->full_name ?? $transaction->payee->name }}
                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                        {{ $transaction->payee->cpf_cnpj }}
                    </span>
                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $transaction->payee->isShopkeeper() ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                        {{ $transaction->payee->isShopkeeper() ? 'Lojista' : 'Usuário Comum' }}
                    </span>
                </dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Valor</dt>
                <dd class="mt-1 text-sm font-medium text-gray-900 sm:mt-0 sm:col-span-2">
                    R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                </dd>
            </div>
            @if($transaction->description)
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Descrição</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $transaction->description }}
                </dd>
            </div>
            @endif
            @if($transaction->authorization_code)
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Código de Autorização</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $transaction->authorization_code }}
                </dd>
            </div>
            @endif
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Status de Notificação</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <div class="flex space-x-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $transaction->payer_notified ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            Remetente: {{ $transaction->payer_notified ? 'Notificado' : 'Pendente' }}
                        </span>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $transaction->payee_notified ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            Destinatário: {{ $transaction->payee_notified ? 'Notificado' : 'Pendente' }}
                        </span>
                    </div>
                </dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Última Atualização</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $transaction->updated_at->format('d/m/Y H:i:s') }}
                </dd>
            </div>
        </dl>
    </div>
    
    <div class="border-t border-gray-200 px-4 py-4 sm:px-6 bg-gray-50 flex justify-between items-center">
        <a href="{{ route('pagamento.history') }}" class="text-sm font-medium text-primary-600 hover:text-primary-800">
            <span aria-hidden="true">&larr;</span> Voltar para Histórico
        </a>
        @if(Auth::user()->canSendMoney() && $transaction->status === 'completed')
        <a href="{{ route('pagamento.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            Nova Transferência
        </a>
        @endif
    </div>
</div>
@endsection
