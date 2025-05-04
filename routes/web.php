<?php

use Illuminate\Support\Facades\Route;
use App\Models\Ticket;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\knowledgeBaseController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//ui user
Route::get('/', function () {
    return view('user/dashboard');
});

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/myaccount', function () {
    return view('user/MyAccount');
});

Route::get('/lacakticket', function () {
    return view('user/lacakticket');
});
Route::get('/Myticket', function () {
    return view('user/Myticket');
});
Route::get('/createticket', function () {
    return view('user/createTicket');
});
Route::get('/faq', function () {
    return view('user/faq');
});
Route::get('/knowledgebase', function () {
    return view('user/knowledgebase');
});
Route::get('/tickets', [TicketController::class, 'myTickets'])->name('tickets.index');

// Group route untuk Developer
Route::middleware(['auth', 'role:1'])->prefix('developer')->name('developer.')->group(function () {
    
    // Dashboard Developer
    Route::get('/dashboard', [DeveloperController::class, 'dashboard'])->name('dashboard');

    // Semua Tiket
    Route::get('/tickets', [DeveloperController::class, 'allTickets'])->name('tickets.index');

    // Detail Tiket
    Route::get('/tickets/{id}', [DeveloperController::class, 'showTicket'])->name('tickets.show');

    // Update Status Tiket
    Route::post('/tickets/{ticketId}/update-status', [DeveloperController::class, 'updateStatus'])->name('tickets.updateStatus');

});

Route::middleware(['auth', 'role:1']) // Middleware role:1 untuk developer
    ->prefix('developer') // URL prefix (/developer/...)
    ->name('developer.') // Nama route prefix (developer.)
    ->group(function () {
        
        // Dashboard Developer
        Route::get('/dashboard', [DeveloperController::class, 'dashboard'])
            ->name('dashboard'); // developer.dashboard

        // Kelola Akun
        Route::get('/kelola-akun', [DeveloperController::class, 'kelolaAkun'])
            ->name('kelola-akun'); // developer.kelola-akun

        // Kelola Tiket
        Route::get('/kelola-ticket', [DeveloperController::class, 'kelolaTicket'])
            ->name('kelola-ticket'); // developer.kelola-ticket

        Route::get('/developermyticket', [DeveloperController::class, 'myticket'])
            ->name('myticket');

        Route::get('/knowledgebase', [DeveloperController::class, 'knowledgebase'])
            ->name('knowledgebase');

        Route::get('/developer/tickets', [DeveloperController::class, 'allTickets'])
            ->name('developer.tickets.index');

        Route::put('/tickets/{ticket}/update-status', [DeveloperController::class, 'updateStatus'])
            ->name('tickets.update-status');
    });
    Route::middleware(['auth', 'role:1'])
    ->post('/tickets/{ticket}/take', [TicketController::class, 'take'])
    ->name('tickets.take');  
    
    Route::middleware(['auth', 'role:1'])->group(function() {
        Route::get('/kelola-tiket', [TicketController::class, 'index'])->name('developer.tickets.index');
        Route::get('/kelola-tiket/{id}', [TicketController::class, 'showdev'])->name('developer.tickets.show');
        Route::get('/kelola-tiket/{id}/edit', [TicketController::class, 'edit'])->name('developer.tickets.edit');
        Route::put('/kelola-tiket/{id}', [TicketController::class, 'update'])->name('developer.tickets.update');
        Route::delete('/kelola-tiket/{id}', [TicketController::class, 'destroy'])->name('developer.tickets.destroy');
        Route::get('/developer/knowledgebase', [KnowledgeBaseController::class, 'index'])
             ->name('developer.knowledgebase');
        Route::get('/developer/kelola-akun', [DeveloperController::class, 'index'])
             ->name('developer.kelola-akun');
        Route::put('/developer/akun/{id}', [DeveloperController::class, 'update'])->name('developer.update');
        Route::delete('/developer/akun/{id}', [DeveloperController::class, 'destroy'])->name('developer.destroy');
        Route::get('/developer/akun/create', [DeveloperController::class, 'create'])->name('developer.create');
        Route::post('/developer/akun', [DeveloperController::class, 'store'])->name('developer.store');     
    });
// Add this route to your routes/web.php
Route::get('/check-roles-schema', function() {
    $columns = DB::getSchemaBuilder()->getColumnListing('roles');
    return response()->json(['columns' => $columns]);
});
// Tiket routes untuk user yang terautentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('tickets.my');
    Route::get('/tickets/create', [TicketController::class, 'createticket'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticketId}/chat', [TicketController::class, 'addChat'])->name('tickets.chat');
    Route::post('/tickets/{ticketId}/documentation', [TicketController::class, 'addDocumentation'])->name('tickets.documentation');
    Route::post('/tickets/{ticketId}/status', [TicketController::class, 'updateStatus'])->name('tickets.status');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::get('/forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
// Protected Routes
Route::middleware(['auth'])->group(function () {
    // User Routes
    Route::middleware(['role:2'])->group(function () {
        Route::get('/user/dashboard', function () {
            return view('user.dashboard');
        })->name('user.dashboard');
        
        Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('tickets.my');
    });
    
    // Developer Routes (role:1)
    Route::middleware(['role:1'])->prefix('developer')->name('developer.')->group(function () {
        Route::get('/dashboard', [DeveloperController::class, 'dashboard'])->name('dashboard');
        Route::get('/tickets', [DeveloperController::class, 'allTickets'])->name('tickets.index');
    });


});