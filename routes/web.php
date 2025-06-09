<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserTicketController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\KnowledgeBaseController;


//== 1. Rute Publik & Tamu (Guest) ==//
Route::get('/', [DashboardController::class, 'index'])->name('welcome'); // Halaman utama

// Rute Otentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rute Knowledge Base Publik
Route::get('/knowledge-base', [KnowledgeBaseController::class, 'publicIndex'])->name('kb.index');
Route::get('/knowledge-base/{knowledgeBase}', [KnowledgeBaseController::class, 'publicShow'])->name('kb.show');


//== 2. Rute Untuk Semua Pengguna yang Sudah Login ==//
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Halaman dashboard utama, akan diarahkan berdasarkan role di AuthController
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Halaman statis dan profil
    Route::get('/faq', [PageController::class, 'faq'])->name('faq');
    Route::get('/my-account', [PageController::class, 'myAccount'])->name('my.account');
    Route::get('/track-ticket', [PageController::class, 'trackTicket'])->name('tickets.track');

    // Aksi terkait profil
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//== 3. Rute Khusus untuk Pengguna Biasa (User) ==//
// Semua rute di sini memiliki prefix URL '/user/' dan nama rute 'user.'
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    // Route::resource akan membuat semua rute standar untuk CRUD tiket (index, create, store, show, destroy)
    Route::resource('tickets', UserTicketController::class)->except(['edit', 'update']);
});


//== 4. Rute Khusus untuk Pengembang (Developer) ==//
// Semua rute di sini memiliki prefix URL '/developer/' dan nama rute 'developer.'
Route::middleware(['auth', 'role:developer'])->prefix('developer')->name('developer.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DeveloperController::class, 'dashboard'])->name('dashboard');

    // Manajemen Tiket oleh Developer
    Route::get('/tickets-new', [DeveloperController::class, 'allTickets'])->name('allTickets'); // Daftar tiket baru
    Route::get('/my-tickets', [DeveloperController::class, 'myTickets'])->name('myTickets'); // Daftar tiket yang ditangani
    Route::get('/my-tickets/{ticket}/edit', [DeveloperController::class, 'editTicket'])->name('tickets.edit'); // Form edit tiket
    Route::get('/manage-tickets', [DeveloperController::class, 'manageTickets'])->name('manageTickets'); // Kelola semua tiket
    Route::get('/tickets/{ticket}', [DeveloperController::class, 'show'])->name('tickets.show'); // Lihat detail tiket
    
    // Aksi pada Tiket
    Route::post('/tickets/{ticket}/assign', [DeveloperController::class, 'assignTicket'])->name('tickets.assign');
    Route::patch('/tickets/{ticket}/complete', [DeveloperController::class, 'completeTicket'])->name('tickets.complete');
    Route::patch('/tickets/{ticket}/status', [DeveloperController::class, 'updateStatus'])->name('tickets.updateStatus');
    Route::post('/tickets/{ticket}/comments', [DeveloperController::class, 'addComment'])->name('comments.add'); // Sesuaikan jika ada
    Route::put('/tickets/{ticket}', [DeveloperController::class, 'updateTicket'])->name('tickets.update'); // Dari modal kelola tiket
    Route::delete('/tickets/{ticket}', [DeveloperController::class, 'destroyTicket'])->name('tickets.destroy'); // Dari modal kelola tiket
    
    // Manajemen Akun
    Route::get('/accounts', [DeveloperController::class, 'kelolaAkun'])->name('kelolaAkun');
    Route::post('/accounts', [DeveloperController::class, 'storeAkun'])->name('akun.store');
    Route::get('/accounts/{account}/edit', [DeveloperController::class, 'editAkun'])->name('accounts.edit');
    Route::put('/accounts/{account}', [DeveloperController::class, 'updateAkun'])->name('akun.update');
    Route::delete('/accounts/{account}', [DeveloperController::class, 'destroyAkun'])->name('akun.destroy');

    // Manajemen Knowledge Base (menggunakan Route::resource)
    // Ini akan otomatis membuat rute: knowledgebase.index, .create, .store, .show, .edit, .update, .destroy
    Route::resource('knowledgebase', KnowledgeBaseController::class)->parameters(['knowledgebase' => 'knowledgeBase']);
});