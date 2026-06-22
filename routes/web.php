<?php

use App\Http\Controllers\Auth\AuthController;
use App\Livewire\Admin\AssetList;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Gudang\Scanner;
use Illuminate\Support\Facades\Route;

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout (authenticated)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/scanner', Scanner::class)->name('scanner');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        Route::get('/assets', AssetList::class)->name('assets');
    });
});
