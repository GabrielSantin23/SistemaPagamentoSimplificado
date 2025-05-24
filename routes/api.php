<?php

use Illuminate\Support\Facades\Route;
use app\Http\Controllers\UserController;

Route::post('/users', [UserController::class, 'store']);

Route::get('/usuario', function () {
    return 'Users Funcionando!';
});