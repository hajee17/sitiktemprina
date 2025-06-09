<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketStatus; 
use App\Models\TicketCategory; 

class DashboardController extends Controller
{
    public function index()
    {
        // Ini sudah benar
        $totalTiket = Ticket::count();

        // Cara yang BENAR untuk menghitung tiket dengan status tertentu
        // Kita query model Ticket, bukan TicketStatus

        // Menghitung tiket yang memiliki relasi 'status' dimana nama statusnya adalah 'In Progress'
        $tiketDiproses = Ticket::whereHas('status', function ($query) {
            $query->where('name', 'In Progress');
        })->count();

        // Menghitung tiket yang memiliki relasi 'status' dimana nama statusnya adalah 'Closed'
        $tiketSelesai = Ticket::whereHas('status', function ($query) {
            $query->where('name', 'Closed');
        })->count();

        return view('user.dashboard', compact('totalTiket', 'tiketDiproses', 'tiketSelesai'));
    }
}