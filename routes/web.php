<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('home');
})->name('home.index');

Route::get('/pagamento', function () {
    return 'Pagamento Funcionando!';
})->name('pagamento.index');

Route::resource('users', UserController::class);