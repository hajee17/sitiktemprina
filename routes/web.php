<?php

use Illuminate\Support\Facades\Route;
use App\Models\Ticket;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\KnowledgeBaseController;

// UI User
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::view('/myaccount', 'user/MyAccount');
Route::view('/lacakticket', 'user/lacakticket');
Route::view('/Myticket', 'user/Myticket');
Route::view('/createticket', 'user/createTicket');
Route::view('/faq', 'user/faq');
Route::view('/knowledgebase', 'user/knowledgebase');

Route::get('/tickets', [TicketController::class, 'myTickets'])->name('tickets.index');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');

// Route Cek Struktur Roles (Dev Only)
Route::get('/check-roles-schema', function () {
    $columns = DB::getSchemaBuilder()->getColumnListing('roles');
    return response()->json(['columns' => $columns]);
});

// Routes untuk User Authenticated Role 2 (User)
Route::middleware(['auth', 'role:2'])->group(function () {
    Route::get('/user/dashboard', [TicketController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('tickets.my');
});

// Routes untuk Developer Role 1
Route::middleware(['auth', 'role:1'])->prefix('developer')->name('developer.')->group(function () {
    Route::get('/dashboard', [DeveloperController::class, 'dashboard'])->name('dashboard');

    // Kelola Tiket
    Route::get('/kelola-tiket', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/kelola-tiket/{id}', [TicketController::class, 'showdev'])->name('tickets.show');
    Route::get('/kelola-tiket/{id}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/kelola-tiket/{id}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/kelola-tiket/{id}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    Route::get('/kelola-ticket', [DeveloperController::class, 'kelolaTicket'])->name('kelola-ticket');
    // Ambil Tiket
    Route::post('/tickets/{ticket}/take', [TicketController::class, 'take'])->name('tickets.take');
    Route::put('/tickets/{ticket}/update-status', [DeveloperController::class, 'updateStatus'])->name('tickets.update-status');
    Route::get('/developermyticket', [DeveloperController::class, 'myticket'])->name('myticket');

    // Kelola Akun
    Route::get('/kelola-akun', [DeveloperController::class, 'index'])->name('kelola-akun');
    Route::put('/akun/{id}', [DeveloperController::class, 'update'])->name('update');
    Route::delete('/akun/{id}', [DeveloperController::class, 'destroy'])->name('destroy');
    Route::get('/akun/create', [DeveloperController::class, 'create'])->name('create');
    Route::post('/akun', [DeveloperController::class, 'store'])->name('store');

    // Knowledgebase
    Route::get('/knowledgebase', [KnowledgeBaseController::class, 'index'])->name('knowledgebase');
});

// Routes Umum untuk User Authenticated
Route::middleware(['auth'])->group(function () {
    Route::get('/tickets/create', [TicketController::class, 'createticket'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticketId}/chat', [TicketController::class, 'addChat'])->name('tickets.chat');
    Route::post('/tickets/{ticketId}/documentation', [TicketController::class, 'addDocumentation'])->name('tickets.documentation');
    Route::post('/tickets/{ticketId}/status', [TicketController::class, 'updateStatus'])->name('tickets.status');
});
