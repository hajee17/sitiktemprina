<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\Account;
use App\Models\TicketStatus;
use App\Models\TicketPriority;
use App\Models\KnowledgeBase;
use App\Models\KnowledgeTag;

class TicketController extends Controller
{
    /**
     * Menampilkan tiket yang belum diambil. (ambil-ticket.blade.php)
     */
    public function index(Request $request)
    {
        $query = Ticket::whereNull('assignee_id')
                    ->where('status_id', 1) // Hanya tiket 'Open'
                    ->with(['author', 'priority', 'status', 'category', 'sbu', 'department']);

        // Logika Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")->orWhere('id', $search);
            });
        }
        
        // Logika Filter Prioritas
        if ($request->filled('priority_id')) {
            $query->where('priority_id', $request->priority_id);
        }
        
        $tickets = $query->latest()->paginate(9)->withQueryString();
        $priorities = TicketPriority::all();

        return view('developer.ambil-ticket', compact('tickets', 'priorities'));
    }
    
    public function show(Ticket $ticket)
    {
        // Eager load relasi untuk ditampilkan di view
        $ticket->load(['author.role', 'priority', 'status', 'category', 'sbu', 'department', 'attachments', 'comments.author']);

        return view('developer.detail-ticket', compact('ticket'));
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
    public function myTickets(Request $request)
    {
        $query = Ticket::where('assignee_id', Auth::id())
                        ->with(['author', 'priority', 'status', 'category'])
                        ->where('status_id', '!=', 4); // Bukan yang sudah 'Closed'

        // Logika Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")->orWhere('id', $search);
            });
        }
        
        // Logika Filter Prioritas
        if ($request->filled('priority_id')) {
            $query->where('priority_id', $request->priority_id);
        }

        // Logika Filter Status
        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        $tickets = $query->latest()->paginate(10)->withQueryString();

        // Data untuk dropdown filter
        $priorities = TicketPriority::all();
        $statuses = TicketStatus::where('name', '!=', 'Open')->get(); // Status yang relevan untuk tiket yg sedang dikerjakan

        return view('developer.myticket', compact('tickets', 'priorities', 'statuses'));
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
        // ... (otorisasi & validasi dari sebelumnya) ...
        $validated = $request->validate([
            'status_id' => 'required|exists:ticket_statuses,id',
            'comment' => 'nullable|string',
            'create_knowledge_base' => 'nullable|boolean',
        ]);

        // Simpan komentar terlebih dahulu jika ada
        if ($request->filled('comment')) {
            $ticket->comments()->create([
                'account_id' => Auth::id(),
                'message' => $validated['comment'],
            ]);
        }

        // Update status tiket
        $ticket->status_id = $validated['status_id'];
        $ticket->save();

        // --- LOGIKA CASE-BASED REASONING (CBR) ---
        $isClosing = \App\Models\TicketStatus::find($validated['status_id'])->name === 'Closed';
        
        if ($isClosing && $request->boolean('create_knowledge_base') && $request->filled('comment')) {
            // Cek apakah sudah ada KB dari tiket ini untuk mencegah duplikat
            if (!$ticket->knowledgeBaseArticle()->exists()) {
                
                // Cari atau buat tag baru berdasarkan kategori tiket
                $tag = KnowledgeTag::firstOrCreate(['name' => $ticket->category->name]);
                
                // Buat artikel Knowledge Base baru
                $kb = KnowledgeBase::create([
                    'title' => $ticket->title,
                    'content' => $validated['comment'], // Konten diambil dari komentar solusi
                    'type' => 'blog',
                    'account_id' => Auth::id(),
                    'source_ticket_id' => $ticket->id, // Tautkan ke tiket sumber
                ]);
                
                // Lampirkan tag ke artikel KB
                $kb->tags()->sync([$tag->id]);
            }
        }

        return redirect()->route('developer.tickets.show', $ticket->id)->with('success', 'Tiket berhasil diperbarui.');
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