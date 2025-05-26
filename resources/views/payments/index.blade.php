@extends('layouts.app')

@section('title', 'Sistema de Pagamentos')

@section('header', 'Sistema de Pagamentos')

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 bg-primary-700 text-white">
        <h3 class="text-lg leading-6 font-medium">Sua Carteira</h3>
        <p class="mt-1 max-w-2xl text-sm text-primary-100">Saldo disponível e informações da sua conta.</p>
    </div>
    <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0">
                <div class="text-sm font-medium text-gray-500">Saldo Disponível</div>
                <div class="mt-1 text-3xl font-bold text-primary-600">R$ {{ number_format($wallet, 2, ',', '.') }}</div>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                @if($user->canSendMoney())
                <a href="{{ route('pagamento.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" />
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Transferir
                </a>
                @endif
                <a href="{{ route('pagamento.history') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                    Histórico
                </a>
            </div>
        </div>
    </div>

    <div class="px-4 py-5 sm:px-6 bg-gray-50">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Informações da Conta</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Detalhes pessoais e informações da sua conta.</p>
    </div>
    <div class="border-t border-gray-200">
        <dl>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Nome completo</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->full_name ?? $user->name }}</dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Tipo de conta</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    @if($user->isShopkeeper())
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                            Lojista
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Usuário Comum
                        </span>
                    @endif
                </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">CPF/CNPJ</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->cpf_cnpj ?? 'Não informado' }}</dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Email</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->email }}</dd>
            </div>
        </dl>
    </div>

    <div class="px-4 py-5 sm:px-6 bg-primary-700 text-white mt-8">
        <h3 class="text-lg leading-6 font-medium">Transações Recentes</h3>
        <p class="mt-1 max-w-2xl text-sm text-primary-100">Últimas transferências enviadas e recebidas.</p>
    </div>

    @if($sentTransactions->count() > 0)
    <div class="border-t border-gray-200">
        <div class="px-4 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
            Enviadas
        </div>
        <ul class="divide-y divide-gray-200">
            @foreach($sentTransactions as $transaction)
            <li>
                <a href="{{ route('pagamento.show', $transaction) }}" class="block hover:bg-gray-50">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        Para: {{ $transaction->payee->full_name ?? $transaction->payee->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-red-600">
                                    - R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                </div>
                                <div class="ml-2">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($transaction->status === 'completed') bg-green-100 text-green-800
                                        @elseif($transaction->status === 'failed') bg-red-100 text-red-800
                                        @elseif($transaction->status === 'reversed') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        @if($transaction->status === 'completed') Concluída
                                        @elseif($transaction->status === 'failed') Falha
                                        @elseif($transaction->status === 'reversed') Revertida
                                        @else Pendente @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($receivedTransactions->count() > 0)
    <div class="border-t border-gray-200">
        <div class="px-4 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
            Recebidas
        </div>
        <ul class="divide-y divide-gray-200">
            @foreach($receivedTransactions as $transaction)
            <li>
                <a href="{{ route('pagamento.show', $transaction) }}" class="block hover:bg-gray-50">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        De: {{ $transaction->payer->full_name ?? $transaction->payer->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-green-600">
                                    + R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                </div>
                                <div class="ml-2">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($transaction->status === 'completed') bg-green-100 text-green-800
                                        @elseif($transaction->status === 'failed') bg-red-100 text-red-800
                                        @elseif($transaction->status === 'reversed') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        @if($transaction->status === 'completed') Concluída
                                        @elseif($transaction->status === 'failed') Falha
                                        @elseif($transaction->status === 'reversed') Revertida
                                        @else Pendente @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($sentTransactions->count() === 0 && $receivedTransactions->count() === 0)
    <div class="border-t border-gray-200 px-4 py-5 sm:p-6 text-center text-gray-500">
        Você ainda não possui transações.
        @if($user->canSendMoney())
            <a href="{{ route('pagamento.create') }}" class="text-primary-600 hover:text-primary-800">Faça sua primeira transferência!</a>
        @endif
    </div>
    @else
    <div class="border-t border-gray-200 px-4 py-4 sm:px-6 text-right">
        <a href="{{ route('pagamento.history') }}" class="text-sm font-medium text-primary-600 hover:text-primary-800">
            Ver histórico completo <span aria-hidden="true">&rarr;</span>
        </a>
    </div>
    @endif
</div>
@endsection
