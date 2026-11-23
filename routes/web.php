<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerpusController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Rute Pengguna
    Route::get('/dashboard', [PerpusController::class, 'userDashboard'])->name('user.dashboard');
    Route::post('/pinjam/{book}', [PerpusController::class, 'pinjamBuku'])->name('pinjam.buku');

        // Rute Admin
    Route::get('/admin/dashboard', [PerpusController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::post('/admin/books', [PerpusController::class, 'storeBook'])->name('admin.books.store');
    Route::delete('/admin/books/{book}', [PerpusController::class, 'destroyBook'])->name('admin.books.destroy');
    Route::post('/admin/kembalikan/{borrowing}', [PerpusController::class, 'kembalikanBuku'])->name('admin.kembalikan');

});
