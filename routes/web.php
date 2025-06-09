<?php

use Illuminate\Support\Facades\Route;
// Import semua controller yang akan kita gunakan di satu tempat
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserTicketController; // Kita anggap TicketController lama adalah UserTicketController
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\PageController; // Controller baru untuk halaman statis

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// == HALAMAN PUBLIK & OTENTIKASI (Bisa diakses semua orang) ==
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
// Rute untuk user melihat knowledge base (tanpa login)
Route::get('/knowledge-base', [KnowledgeBaseController::class, 'publicIndex'])->name('knowledgebase.public');
Route::get('/knowledge-base/{knowledgeBase}', [KnowledgeBaseController::class, 'publicShow'])->name('knowledgebase.public.show');


// --- Auth Routes ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// == HALAMAN UNTUK PENGGUNA YANG SUDAH LOGIN (ROLE APAPUN) ==
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/my-account', [PageController::class, 'myAccount'])->name('my-account');

    // --- Rute Tiket untuk Pengguna Biasa ---
    // Logika ini sekarang dihandle oleh UserTicketController
    Route::prefix('my-tickets')->name('user.tickets.')->group(function () {
        Route::get('/', [UserTicketController::class, 'index'])->name('index');
        Route::get('/create', [UserTicketController::class, 'create'])->name('create');
        Route::post('/', [UserTicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [UserTicketController::class, 'show'])->name('show');
        Route::post('/{ticket}/comments', [UserTicketController::class, 'addComment'])->name('comments.add');
    });
});


// == HALAMAN KHUSUS UNTUK DEVELOPER ==
Route::middleware(['auth', 'role:developer'])->prefix('developer')->name('developer.')->group(function () {
    
    Route::get('/dashboard', [DeveloperController::class, 'dashboard'])->name('dashboard');

    // --- Manajemen Tiket oleh Developer ---
    Route::get('/tickets/all', [DeveloperController::class, 'allTickets'])->name('tickets.all'); // Tiket baru yang belum diambil
    Route::get('/tickets/my', [DeveloperController::class, 'myTickets'])->name('tickets.my'); // Tiket yang sedang dikerjakan
    Route::post('/tickets/{ticket}/assign', [DeveloperController::class, 'assignTicket'])->name('tickets.assign'); // Aksi mengambil tiket
    Route::post('/tickets/{ticket}/complete', [DeveloperController::class, 'completeTicket'])->name('tickets.complete'); // Aksi menyelesaikan tiket
    
    // --- Manajemen Akun oleh Developer ---
    Route::get('/accounts', [DeveloperController::class, 'kelolaAkun'])->name('accounts.index');
    Route::post('/accounts', [DeveloperController::class, 'storeAkun'])->name('accounts.store');
    Route::put('/accounts/{account}', [DeveloperController::class, 'updateAkun'])->name('accounts.update');
    Route::delete('/accounts/{account}', [DeveloperController::class, 'destroyAkun'])->name('accounts.destroy');

    // --- Manajemen Knowledge Base oleh Developer (CRUD) ---
    // Menggunakan Route::resource untuk otomatis membuat rute CRUD
    Route::resource('knowledgebase', KnowledgeBaseController::class);
});