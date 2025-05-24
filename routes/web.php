<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/teste-web', function () {
    return 'Teste Web Funcionando!';
});

Route::get('/cadastro', function () {
    return 'Teste Cadastro Funcionando!';
})->name('cadastro.index');

Route::get('/pagamento', function () {
    return 'Pagamento Funcionando!';
})->name('pagamento.index');