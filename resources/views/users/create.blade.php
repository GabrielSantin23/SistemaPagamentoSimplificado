@extends('layouts.app')

@section('title', 'Criar Novo Usuário')

@section('header', 'Criar Novo Usuário')

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                    <div class="mt-1">
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm h-8 border border-gray-300 rounded-md @error('name') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cpf_cnpj" class="block text-sm font-medium text-gray-700">CPF ou CNPJ</label>
                    <div class="mt-1">
                        <input type="text" name="cpf_cnpj" id="cpf_cnpj" value="{{ old("cpf_cnpj") }}" required placeholder="Digite seu CPF ou CNPJ"
                            class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm h-8 border border-gray-300 rounded-md @error("cpf_cnpj") border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                    </div>
                    @error("cpf_cnpj")
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="user_type" class="block text-sm font-medium text-gray-700">Tipo de usuário</label>
                    <div class="mt-1">
                        <select id="user_type" name="user_type" required
                                class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm h-8 border border-gray-300 rounded-md @error('user_type') border-red-300 text-red-900 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            <option value="" disabled selected>Selecione o tipo</option>
                            <option value="COMUM" {{ old('user_type') == 'COMUM' ? 'selected' : '' }}>COMUM</option>
                            <option value="LOJISTA" {{ old('user_type') == 'LOJISTA' ? 'selected' : '' }}>LOJISTA</option>
                        </select>
                    </div>
                    @error('user_type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="wallet" class="block text-sm font-medium text-gray-700">Carteira (Saldo)</label>
                    <div class="mt-1">
                        <input type="wallet" name="wallet" id="wallet" value="{{ old('wallet') }}" required
                               class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm h-8 border border-gray-300 rounded-md @error('wallet') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                    </div>
                    @error('wallet')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="mt-1">
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm h-8 border border-gray-300 rounded-md @error('email') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                    <div class="mt-1">
                        <input type="password" name="password" id="password" required
                               class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm h-8 border border-gray-300 rounded-md @error('password') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        A senha deve ter pelo menos 8 caracteres.
                    </p>
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Senha</label>
                    <div class="mt-1">
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm h-8 border border-gray-300 rounded-md">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Salvar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
