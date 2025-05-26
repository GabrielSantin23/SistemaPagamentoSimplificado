@extends('layouts.app')

@section('title', 'Histórico de Transações')

@section('header', 'Histórico de Transações')

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 bg-primary-700 text-white">
        <h3 class="text-lg leading-6 font-medium">Histórico Completo</h3>
        <p class="mt-1 max-w-2xl text-sm text-primary-100">Todas as suas transferências enviadas e recebidas.</p>
    </div>
    
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex" x-data="{ activeTab: 'sent' }">
            <button @click="activeTab = 'sent'" :class="{ 'border-primary-500 text-primary-600': activeTab === 'sent', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'sent' }" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm">
                Enviadas ({{ $sentTransactions->count() }})
            </button>
            <button @click="activeTab = 'received'" :class="{ 'border-primary-500 text-primary-600': activeTab === 'received', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'received' }" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm">
                Recebidas ({{ $receivedTransactions->count() }})
            </button>
        </nav>
    </div>
    
    <div x-show="activeTab === 'sent'">
        @if($sentTransactions->count() > 0)
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
                                    @if($transaction->description)
                                    <div class="text-sm text-gray-500 mt-1 italic">
                                        "{{ Str::limit($transaction->description, 50) }}"
                                    </div>
                                    @endif
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
        @else
        <div class="px-4 py-5 sm:p-6 text-center text-gray-500">
            Você ainda não enviou nenhuma transferência.
            @if($user->canSendMoney())
                <a href="{{ route('pagamento.create') }}" class="text-primary-600 hover:text-primary-800">Faça sua primeira transferência!</a>
            @endif
        </div>
        @endif
    </div>
    
    <div x-show="activeTab === 'received'">
        @if($receivedTransactions->count() > 0)
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
                                    @if($transaction->description)
                                    <div class="text-sm text-gray-500 mt-1 italic">
                                        "{{ Str::limit($transaction->description, 50) }}"
                                    </div>
                                    @endif
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
        @else
        <div class="px-4 py-5 sm:p-6 text-center text-gray-500">
            Você ainda não recebeu nenhuma transferência.
        </div>
        @endif
    </div>
    
    <div class="border-t border-gray-200 px-4 py-4 sm:px-6 bg-gray-50 flex justify-between items-center">
        <a href="{{ route('pagamento.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-800">
            <span aria-hidden="true">&larr;</span> Voltar para Pagamentos
        </a>
        @if($user->canSendMoney())
        <a href="{{ route('pagamento.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            Nova Transferência
        </a>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    
</script>
@endsection
