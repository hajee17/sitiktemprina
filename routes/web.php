<?php

use Illuminate\Support\Facades\Route;
use App\Models\Ticket;
use App\Models\Account;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;

// Route utama
Route::get('/', [DashboardController::class, 'index'])->name('dashboard'); // Menampilkan dashboard pada homepage
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // Dashboard pengguna

// Halaman User
Route::get('/myaccount', function () {
    return view('user/MyAccount');
});
Route::get('/lacakticket', function () {
    return view('user/lacakticket');
});
Route::get('/Myticket', function () {
    return view('user/Myticket');
});
Route::get('/faq', function () {
    return view('user/faq');
});

// Route Tiket
Route::get('/tickets', [TicketController::class, 'myTickets'])->name('tickets.index');
Route::get('/ticket/create', [TicketController::class, 'create'])->name('ticket.create');
Route::post('/ticket/store', [TicketController::class, 'store'])->name('ticket.store');
// Route Developer
Route::get('/ambil-ticket', function () {
    return view('developer/ambil-ticket');
});
Route::get('/detail-ticket', function () {
    return view('developer/detail-ticket');
});
Route::get('/dashboard-dev', function () {
    return view('developer/dashboard');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Protected Routes (Hanya untuk user yang terautentikasi)
Route::middleware(['auth'])->group(function () {

    // User Routes
    Route::middleware(['role:2'])->group(function () {
        Route::get('/user/dashboard', function () {
            return view('user.dashboard');
        })->name('user.dashboard');
        
        Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('tickets.my');
    });
    
    // Developer Routes
    Route::middleware(['role:1'])->group(function () {
        Route::get('/developer/dashboard', function () {
            return view('developer.dashboard');
        })->name('developer.dashboard');
    });

});
