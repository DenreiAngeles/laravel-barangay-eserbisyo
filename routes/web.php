<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;


// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});

// Protected routes
Route::middleware('auth')->group(function () {
    // Home Dashboard (NEW - Main landing page after login)
    Route::get('/', [ResidentController::class, 'home'])->name('resident.home');

    // Profile Page (OLD dashboard content)
    Route::get('/profile', [ResidentController::class, 'profile'])->name('resident.profile');

    // Other Pages
    Route::get('/tickets', [TicketController::class, 'index'])->name('resident.tickets');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('resident.tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('resident.tickets.store');
    Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('resident.tickets.show');
    Route::post('/tickets/{id}/comment', [TicketController::class, 'addComment'])->name('resident.tickets.addComment');
    Route::get('/documents', [ResidentController::class, 'documents'])->name('resident.documents');
    Route::get('/transparency', [ResidentController::class, 'transparency'])->name('resident.transparency');
    Route::get('/map', [ResidentController::class, 'map'])->name('resident.map');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
