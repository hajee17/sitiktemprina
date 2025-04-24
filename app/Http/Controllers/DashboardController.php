<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\Status;

class DashboardController extends Controller
{
    public function index()
{
    $totalTiket = Ticket::count();
    $tiketDiproses = Status::where('Status', 'Diproses')->count();
    $tiketSelesai = Status::where('Status', 'Selesai')->count();

    return view('user.dashboard', compact('totalTiket', 'tiketDiproses', 'tiketSelesai'));
}
}
