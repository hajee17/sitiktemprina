<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\TicketController as UserTicketController;
use App\Http\Controllers\User\KnowledgeBaseController as UserKnowledgeBaseController;
use App\Http\Controllers\User\PageController as UserPageController; 
use App\Http\Controllers\Developer\DashboardController as DeveloperDashboardController;
use App\Http\Controllers\Developer\TicketController as DeveloperTicketController;
use App\Http\Controllers\Developer\AccountController as DeveloperAccountController;
use App\Http\Controllers\Developer\KnowledgeBaseController as DeveloperKnowledgeBaseController;
use App\Http\Controllers\TicketCommentController;
use App\Http\Controllers\Developer\TagController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan rute web untuk aplikasi Anda. Rute-rute
| ini dimuat oleh RouteServiceProvider dan semuanya akan
| ditetapkan ke grup middleware "web".
|
*/

// --- Rute Publik (Guest) ---
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// --- Rute Otentikasi (Login, Register, Logout, Google Auth) ---
Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'showLoginForm')->name('login')->middleware('guest');
    Route::post('login', 'login')->middleware('guest');
    Route::get('register', 'showRegistrationForm')->name('register')->middleware('guest');
    Route::post('register', 'register')->middleware('guest');
    
    // Rute logout harus diakses oleh user yang sudah login
    Route::post('logout', 'logout')->name('logout')->middleware('auth');

    // Rute untuk otentikasi Google
    Route::get('auth/google', 'redirectToGoogle')->name('login.google')->middleware('guest');
    Route::get('auth/google/callback', 'handleGoogleCallback')->middleware('guest');
});


// --- Grup Rute untuk Pengguna yang Sudah Terotentikasi ---
Route::middleware(['auth'])->group(function () {

    // Redirect /dashboard ke dashboard yang sesuai dengan peran user
    Route::get('/dashboard', function () {
        if (Auth::user()->isDeveloper()) {
            return redirect()->route('developer.dashboard');
        }
        return redirect()->route('user.dashboard');
    })->name('dashboard');

    // --- Grup Rute untuk Peran 'USER' ---
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

        // Resourceful-like routes untuk tiket user
        Route::get('/tickets', [UserTicketController::class, 'index'])->name('tickets.index'); // MyTicket.blade.php
        Route::get('/tickets/create', [UserTicketController::class, 'create'])->name('tickets.create'); // createTicket.blade.php
        Route::post('/tickets', [UserTicketController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{ticket}', [UserTicketController::class, 'show'])->name('tickets.show'); // LacakTicket.blade.php
        Route::post('/tickets/{ticket}/cancel', [UserTicketController::class, 'cancel'])->name('tickets.cancel');
        Route::post('/tickets/{ticket}/comments', [TicketCommentController::class, 'store'])->name('comments.store');
        // Rute untuk Knowledge Base
        Route::get('/knowledgebase', [UserKnowledgeBaseController::class, 'index'])->name('knowledgebase.index');
        Route::get('/knowledgebase/{knowledgeBase}', [UserKnowledgeBaseController::class, 'show'])->name('knowledgebase.show');

        // Rute untuk Halaman Lainnya
        Route::get('/my-account', [UserPageController::class, 'myAccount'])->name('account');
        Route::get('/faq', [UserPageController::class, 'faq'])->name('faq');
    });


    // --- Grup Rute untuk Peran 'DEVELOPER' ---
    // Middleware 'role:developer' akan memastikan hanya developer yang bisa akses
    Route::prefix('developer')->name('developer.')->middleware('role:developer')->group(function () {
        
        Route::get('/dashboard', [DeveloperDashboardController::class, 'index'])->name('dashboard');

        // Rute untuk Manajemen Tiket oleh Developer
        Route::controller(DeveloperTicketController::class)->group(function () {
            Route::get('/tickets', 'index')->name('tickets.index'); // Ambil Tiket
            Route::post('/tickets/{ticket}/take', 'take')->name('tickets.take');
            Route::get('/my-tickets', 'myTickets')->name('myticket');
            Route::get('/manage-tickets', 'manageAll')->name('kelola-ticket');
            Route::put('/tickets/{ticket}', 'update')->name('tickets.update');
            Route::delete('/tickets/{ticket}', 'destroy')->name('tickets.destroy');
            // Jika ada detail view spesifik untuk developer, tambahkan di sini.
            Route::get('/tickets/{ticket}/detail', 'show')->name('tickets.show'); 
            
        });
        Route::resource('tags', TagController::class)->except(['show', 'create', 'edit']);
        Route::get('/developer/kelola-ticket', [DeveloperTicketController::class, 'index'])->name('developer.kelola-ticket');

        // Rute untuk Manajemen Akun (CRUD)
        // Menggunakan Route::resource akan secara otomatis membuat rute index, create, store, show, edit, update, destroy
        Route::resource('akun', DeveloperAccountController::class);
        
        Route::post('/tickets/{ticket}/comments', [TicketCommentController::class, 'store'])->name('comments.store');
        Route::resource('knowledgebase', DeveloperKnowledgeBaseController::class);

    });
});