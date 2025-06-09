<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DeveloperController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        // Ambil ID dari status yang relevan sekali
        $statusOpenId = TicketStatus::where('name', 'Open')->value('id');
        $statusInProgressId = TicketStatus::where('name', 'In Progress')->value('id');
        $statusClosedId = TicketStatus::where('name', 'Closed')->value('id');
        $priorityHighId = \App\Models\TicketPriority::where('name', 'Tinggi')->value('id');

        $totalTickets = Ticket::count();
        $newTickets = Ticket::where('status_id', $statusOpenId)->count();

        $counts = [
            'highPriorityNew' => Ticket::where('priority_id', $priorityHighId)->where('status_id', $statusOpenId)->count(),
            'newTickets' => $newTickets,
            'processedTickets' => Ticket::where('status_id', $statusInProgressId)->count(),
            'completedTickets' => Ticket::where('status_id', $statusClosedId)->count(),
            'totalTickets' => $totalTickets,
            'myAssignedTickets' => Ticket::where('assignee_id', auth()->id())->where('status_id', $statusInProgressId)->count(),
        ];
        
        $weeklyData = $this->getWeeklyTicketData();

        $latestTickets = Ticket::with('author', 'status')->latest()->take(5)->get();

        return view('developer.dashboard', array_merge($counts, $weeklyData, ['latestTickets' => $latestTickets]));
    }
    
    protected function getWeeklyTicketData()
    {
        $weekStartDate = Carbon::now()->startOfWeek();
        $weekEndDate = Carbon::now()->endOfWeek();
        
        $ticketsByDay = Ticket::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$weekStartDate, $weekEndDate])
            ->groupBy('date')
            ->pluck('count', 'date');

        $weekDays = [];
        $weeklyData = [];

        for ($i = 0; $i < 7; $i++) {
            $day = $weekStartDate->copy()->addDays($i);
            $weekDays[] = $day->isoFormat('ddd');
            $weeklyData[] = $ticketsByDay->get($day->toDateString(), 0);
        }
        
        return ['weekDays' => $weekDays, 'weeklyData' => $weeklyData];
    }

    /**
     * Menampilkan semua tiket yang belum ditugaskan (status 'Open').
     */
    public function allTickets()
    {
        $statusOpenId = TicketStatus::where('name', 'Open')->value('id');
        $tickets = Ticket::with('author', 'status', 'priority')
            ->where('status_id', $statusOpenId)
            ->latest()
            ->paginate(15);

        return view('developer.all-tickets', compact('tickets'));
    }

    /**
     * Menampilkan tiket yang sedang ditangani oleh developer yang login.
     */
    public function myTickets()
    {
        $tickets = Ticket::with('author', 'status', 'priority')
            ->where('assignee_id', auth()->id()) // Query berdasarkan kolom assignee_id
            ->whereHas('status', fn($q) => $q->where('name', '!=', 'Closed')) // Tampilkan yang belum selesai
            ->latest()
            ->paginate(15);
            
        return view('developer.my-tickets', compact('tickets'));
    }

    /**
     * Aksi untuk mengambil (assign) sebuah tiket.
     */
    public function assignTicket(Ticket $ticket)
    {
        $statusInProgress = TicketStatus::where('name', 'In Progress')->first();

        // Assign tiket ke diri sendiri dan ubah statusnya
        $ticket->update([
            'assignee_id' => auth()->id(),
            'status_id' => $statusInProgress->id,
        ]);

        return redirect()->route('developer.myTickets')->with('success', 'Tiket berhasil diambil.');
    }
    
    /**
     * Aksi untuk menandai tiket sebagai selesai.
     */
    public function completeTicket(Ticket $ticket)
    {
        if ($ticket->assignee_id !== auth()->id()) {
            return back()->with('error', 'Anda tidak berhak mengubah status tiket ini.');
        }

        $statusClosed = TicketStatus::where('name', 'Closed')->first();

        $ticket->update([
            'status_id' => $statusClosed->id,
        ]);

        return redirect()->route('developer.myTickets')->with('success', 'Tiket telah ditandai sebagai selesai.');
    }


    // --- KELOLA AKUN ---

    public function kelolaAkun()
    {
        $accounts = Account::with('role')->latest()->paginate(10);
        $roles = Role::all();
        
        return view('developer.kelola-akun', compact('accounts', 'roles'));
    }
    
    public function updateAkun(Request $request, Account $account)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('accounts')->ignore($account->id)],
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
        ]);
    
        $account->update($validated);
    
        return back()->with('success', 'Akun berhasil diperbarui.');
    }
    
    public function destroyAkun(Account $account)
    {
        // Pencegahan agar tidak bisa menghapus diri sendiri
        if ($account->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $account->delete();
        return back()->with('success', 'Akun berhasil dihapus.');
    }

    public function storeAkun(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:accounts,email',
            'username' => 'required|string|unique:accounts,username',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        Account::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
        ]);

        return redirect()->route('developer.kelolaAkun')->with('success', 'Akun baru berhasil ditambahkan.');
    }
}