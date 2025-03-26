<?php

use Illuminate\Support\Facades\Route;
use App\Models\Ticket;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
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

Route::get('/', function () {
    return view('dashboard');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
});


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/myaccount', function () {
    return view('MyAccount');
});
Route::get('/login', function () {
    return view('login');
});
Route::get('/register', function () {
    return view('register');
});
Route::get('/lacakticket', function () {
    return view('lacakticket');
});
Route::get('/Myticket', function () {
    return view('Myticket');
});
Route::get('/faq', function () {
    return view('faq');
});
