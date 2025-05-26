<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentsController;

Route::middleware(['auth'])->group(function () {
    Route::get('/pagamento', [PaymentsController::class, 'index'])->name('pagamento.index');
    
    Route::get('/pagamento/transferir', [PaymentsController::class, 'create'])->name('pagamento.create');
    
    Route::post('/pagamento/transferir', [PaymentsController::class, 'store'])->name('pagamento.store');
    
    Route::get('/pagamento/historico', [PaymentsController::class, 'history'])->name('pagamento.history');
    
    Route::get('/pagamento/transacao/{transaction}', [PaymentsController::class, 'show'])->name('pagamento.show');
});
