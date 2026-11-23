<?php

use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'index'])->name('storefront.index');
Route::get('/topup/{product:slug}', [StorefrontController::class, 'show'])->name('products.show');
Route::post('/topup/{product:slug}', [TransactionController::class, 'store'])->name('transactions.store');

Route::get('/payment/{invoice_code}', [StorefrontController::class, 'payment'])->name('payments.show');
Route::post('/payment/{invoice_code}/success', [TransactionController::class, 'simulateSuccess'])
    ->name('payments.simulate-success');
Route::post('/payment/{invoice_code}/failed', [TransactionController::class, 'simulateFailure'])
    ->name('payments.simulate-failure');

Route::get('/invoice/{invoice_code}', [StorefrontController::class, 'invoice'])->name('invoices.show');
Route::get('/cek-transaksi', [StorefrontController::class, 'check'])->name('transactions.check');
