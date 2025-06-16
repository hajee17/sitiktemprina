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


Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'showLoginForm')->name('login')->middleware('guest');
    Route::post('login', 'login')->middleware('guest');
    Route::get('register', 'showRegistrationForm')->name('register')->middleware('guest');
    Route::post('register', 'register')->middleware('guest');
    
    Route::post('logout', 'logout')->name('logout')->middleware('auth');

    Route::get('auth/google', 'redirectToGoogle')->name('login.google')->middleware('guest');
    Route::get('auth/google/callback', 'handleGoogleCallback')->middleware('guest');
});


Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        if (Auth::user()->isDeveloper()) {
            return redirect()->route('developer.dashboard');
        }
        return redirect()->route('user.dashboard');
    })->name('dashboard');

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::get('/tickets', [UserTicketController::class, 'index'])->name('tickets.index'); 
        Route::get('/tickets/create', [UserTicketController::class, 'create'])->name('tickets.create'); 
        Route::post('/tickets', [UserTicketController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{ticket}', [UserTicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{ticket}/cancel', [UserTicketController::class, 'cancel'])->name('tickets.cancel');
        Route::post('/tickets/{ticket}/comments', [TicketCommentController::class, 'store'])->name('comments.store');
        
        // Rute untuk Knowledge Base
        Route::get('/knowledgebase', [UserKnowledgeBaseController::class, 'index'])->name('knowledgebase.index');
        Route::get('/knowledgebase/{knowledgeBase}', [UserKnowledgeBaseController::class, 'show'])->name('knowledgebase.show');

        // Rute untuk Halaman Lainnya
        Route::get('/my-account', [UserPageController::class, 'myAccount'])->name('account');
        Route::get('/faq', [UserPageController::class, 'faq'])->name('faq');

        Route::get('/change-password', [UserPageController::class, 'showChangePasswordForm'])->name('password.form');
        Route::put('/change-password', [UserPageController::class, 'changePassword'])->name('password.update');

    });

    Route::prefix('developer')->name('developer.')->middleware('role:developer')->group(function () {
        
        Route::get('/dashboard', [DeveloperDashboardController::class, 'index'])->name('dashboard');

        Route::controller(DeveloperTicketController::class)->group(function () {
            Route::get('/tickets', 'index')->name('tickets.index');
            Route::post('/tickets/{ticket}/take', 'take')->name('tickets.take');
            Route::get('/my-tickets', 'myTickets')->name('myticket');
            Route::get('/manage-tickets', 'manageAll')->name('kelola-ticket'); 
            Route::put('/tickets/{ticket}', 'update')->name('tickets.update');
            Route::delete('/tickets/{ticket}', 'destroy')->name('tickets.destroy');
            Route::get('/tickets/{ticket}/detail', 'show')->name('tickets.show'); 
            
        });
        Route::resource('tags', TagController::class)->except(['show', 'create', 'edit']);

        Route::resource('akun', DeveloperAccountController::class);
        
        Route::post('/tickets/{ticket}/comments', [TicketCommentController::class, 'store'])->name('comments.store');
        Route::resource('knowledgebase', DeveloperKnowledgeBaseController::class);

    });
});