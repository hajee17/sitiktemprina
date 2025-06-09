<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketStatus; 
use App\Models\TicketCategory; 
use Illuminate\Support\Facades\Auth;
class DashboardController extends Controller
{
    public function index()
{
    $userId = Auth::id();

    // 1. Statistik spesifik untuk user yang login
    $totalTiket = Ticket::where('account_id', $userId)->count();
    
    $tiketDiproses = Ticket::where('account_id', $userId)
        ->whereHas('status', fn($q) => $q->where('name', 'In Progress'))
        ->count();

    $tiketSelesai = Ticket::where('account_id', $userId)
        ->whereHas('status', fn($q) => $q->where('name', 'Closed'))
        ->count();
    
    // 2. Menyediakan data kategori dari database
    $categories = TicketCategory::all();

    // Menggunakan nama file Anda: 'dashboard.blade.php' di folder user
    return view('user.dashboard', compact('totalTiket', 'tiketDiproses', 'tiketSelesai', 'categories'));
}
}