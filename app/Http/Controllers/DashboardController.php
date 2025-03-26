<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalTickets' => Ticket::count(),
            'inProgressTickets' => Ticket::where('status', 'in_progress')->count(),
            'resolvedTickets' => Ticket::where('status', 'resolved')->count(),
            'categories' => Category::all(),
        ]);
    }
}
