<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        $totalTiket = Ticket::where('account_id', $userId)->count();
        $tiketDiproses = Ticket::where('account_id', $userId)->where('status_id', 2)->count();
        $tiketSelesai = Ticket::where('account_id', $userId)->where('status_id', 4)->count(); 

        return view('user.dashboard', compact('totalTiket', 'tiketDiproses', 'tiketSelesai'));
    }
}