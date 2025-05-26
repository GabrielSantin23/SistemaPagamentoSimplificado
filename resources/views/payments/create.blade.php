@extends('layouts.app')

@section('title', 'Realizar Transferência')

@section('header', 'Nova Transferência')

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 bg-primary-700 text-white">
        <h3 class="text-lg leading-6 font-medium">Transferir Dinheiro</h3>
        <p class="mt-1 max-w-2xl text-sm text-primary-100">Envie dinheiro para outros usuários ou lojistas.</p>
    </div>
    
    <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
        
        <form action="{{ route('pagamento.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="cpf_cnpj" class="block text-sm font-medium text-gray-700">CPF/CNPJ do Destinatário</label>
                    <div class="mt-1">
                        <input type="text" name="cpf_cnpj" id="cpf_cnpj" value="{{ old('cpf_cnpj') }}" required
                               class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md @error('cpf_cnpj') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                               placeholder="Digite o CPF ou CNPJ sem pontuação">
                    </div>
                    @error('cpf_cnpj')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Digite o CPF (para usuários comuns) ou CNPJ (para lojistas) sem pontuação.
                    </p>
                </div>
                
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700">Valor</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">R$</span>
                        </div>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required
                               min="0.01" step="0.01"
                               class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md @error('amount') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                               placeholder="0,00">
                    </div>
                    @error('amount')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Descrição (opcional)</label>
                    <div class="mt-1">
                        <textarea name="description" id="description" rows="3"
                                  class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md @error('description') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                  placeholder="Adicione uma descrição para esta transferência">{{ old('description') }}</textarea>
                    </div>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('pagamento.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Transferir
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <div class="border-t border-gray-200 px-4 py-5 sm:px-6 bg-gray-50">
        <h4 class="text-sm font-medium text-gray-500 mb-2">Informações Importantes</h4>
        <ul class="list-disc pl-5 text-sm text-gray-500 space-y-1">
            <li>Transferências são processadas instantaneamente.</li>
            <li>Você só pode transferir se tiver saldo suficiente.</li>
            <li>Todas as transferências passam por um serviço autorizador externo.</li>
            <li>Tanto você quanto o destinatário receberão uma notificação após a transferência.</li>
            <li>Em caso de falha, o valor será estornado automaticamente para sua carteira.</li>
        </ul>
    </div>
</div>
@endsection
