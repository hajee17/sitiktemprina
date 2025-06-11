<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Data untuk kartu statistik
        $newTickets = Ticket::where('status_id', 1)->count(); // Open
        $processedTickets = Ticket::where('status_id', 2)->count(); // In Progress
        $completedTickets = Ticket::where('status_id', 4)->count(); // Closed
        $totalTickets = Ticket::count();
        $highPriorityNew = Ticket::where('status_id', 1)->where('priority_id', 1)->count(); // High Priority

        // Data untuk tabel tiket terbaru
        $latestTickets = Ticket::with(['priority', 'status'])->latest()->take(5)->get();

        // Data untuk grafik mingguan
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $weeklyDataRaw = Ticket::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');
        
        $weekDays = [];
        $weeklyData = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $dayString = $day->format('Y-m-d');
            $weekDays[] = $day->format('D'); // Mon, Tue, etc.
            $weeklyData[] = $weeklyDataRaw->get($dayString)->count ?? 0;
        }

        // Data untuk grafik distribusi status
        $statusDistribution = Ticket::select('status_id', DB::raw('count(*) as count'))
            ->with('status')
            ->groupBy('status_id')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status->name => $item->count];
            })->toArray();
        
        return view('developer.dashboard', compact(
            'newTickets', 'processedTickets', 'completedTickets', 'totalTickets', 'highPriorityNew',
            'latestTickets', 'weekDays', 'weeklyData', 'statusDistribution'
        ));
    }
}