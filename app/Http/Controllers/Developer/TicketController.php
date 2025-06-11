<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\Account;
use App\Models\TicketStatus;

class TicketController extends Controller
{
    /**
     * Menampilkan tiket yang belum diambil. (ambil-ticket.blade.php)
     */
    public function index(Request $request)
    {
        $query = Ticket::whereNull('assignee_id')
                    ->where('status_id', 1) // Hanya tiket 'Open'
                    ->with(['author', 'priority', 'status', 'category']);

        // Logika Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('id', $search)
                  ->orWhereHas('author', function($q_author) use ($search) {
                      $q_author->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Logika Filter Kategori
        if ($request->filled('category') && $request->category !== 'Semua') {
            $query->whereHas('category', function($q_cat) use ($request) {
                $q_cat->where('name', $request->category);
            });
        }
        
        $tickets = $query->latest()->paginate(9);

        return view('developer.ambil-ticket', compact('tickets'));
    }
    
    /**
     * Developer mengambil tiket.
     */
    public function take(Ticket $ticket)
    {
        if ($ticket->assignee_id === null && $ticket->status_id == 1) {
            $ticket->assignee_id = Auth::id();
            $ticket->status_id = 2; // In Progress
            $ticket->save();
            return redirect()->route('developer.myticket')->with('success', 'Tiket berhasil diambil.');
        }
        return back()->with('error', 'Tiket ini sudah diambil atau tidak lagi tersedia.');
    }

    /**
     * Menampilkan tiket yang sedang ditangani developer. (myticket.blade.php)
     */
    public function myTickets()
    {
        $tickets = Ticket::where('assignee_id', Auth::id())
                        ->with(['author', 'priority', 'status', 'category', 'attachments', 'comments'])
                        ->where('status_id', '!=', 4) // Bukan yang sudah 'Closed'
                        ->latest()
                        ->paginate(5);
        return view('developer.myticket', compact('tickets'));
    }
    
    /**
     * Menampilkan halaman kelola semua tiket. (kelola-ticket.blade.php)
     */
    public function manageAll(Request $request)
    {
        // Data Statistik
        $stats = [
            'total_tickets' => Ticket::count(),
            'open' => Ticket::where('status_id', 1)->count(),
            'in_progress' => Ticket::where('status_id', 2)->count(),
            'closed' => Ticket::where('status_id', 4)->count(),
        ];
        
        // Query utama
        $tickets = Ticket::with(['author', 'assignee', 'priority', 'status', 'category'])
            ->latest()
            ->paginate(15);
        
        $statuses = TicketStatus::all();

        return view('developer.kelola-ticket', compact('tickets', 'stats', 'statuses'));
    }

    /**
     * Update tiket dari modal.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status_id' => 'required|exists:ticket_statuses,id',
        ]);

        $ticket->update($request->only('title', 'status_id'));
        
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Tiket berhasil diperbarui.']);
        }

        return back()->with('success', 'Tiket berhasil diperbarui.');
    }
    
    /**
     * Hapus tiket.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return back()->with('success', 'Tiket berhasil dihapus permanen.');
    }
}