<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\AuthController;

// --- GUEST ROUTES (Accessible only when NOT logged in) ---
Route::middleware('guest')->group(function () {
    // Login Pages
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    // Register Pages
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Forgot Password Pages
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});

// --- PROTECTED ROUTES (Accessible only when logged in) ---
Route::get('/', [ResidentController::class, 'index'])->name('resident.dashboard');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');