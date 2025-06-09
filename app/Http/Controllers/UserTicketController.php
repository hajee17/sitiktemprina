<?php

namespace App\Http\Controllers;

use App\Models\Sbu;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan daftar tiket milik pengguna yang sedang login.
     */
    public function index()
    {
        $tickets = Ticket::with('status', 'priority')
            ->where('account_id', auth()->id()) // Query tiket berdasarkan ID pembuat
            ->latest()
            ->paginate(10);

        return view('user.tickets.index', compact('tickets'));
    }

    /**
     * Menampilkan form untuk membuat tiket baru.
     */
    public function create()
    {
        // Ambil data dari database untuk mengisi dropdown di form
        $categories = TicketCategory::all();
        $priorities = TicketPriority::orderBy('level')->get();
        $sbus = Sbu::all();
        $departments = Department::all();

        return view('user.tickets.create', compact('categories', 'priorities', 'sbus', 'departments'));
    }

    /**
     * Menyimpan tiket baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:ticket_categories,id',
            'priority_id' => 'required|exists:ticket_priorities,id',
            'sbu_id' => 'required|exists:sbus,id',
            'department_id' => 'required|exists:departments,id',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', // Maks 5MB
        ]);

        // Ambil status 'Open' sebagai status awal
        $statusOpen = TicketStatus::where('name', 'Open')->first();
        if (!$statusOpen) {
            return back()->with('error', 'Konfigurasi status sistem tidak ditemukan.')->withInput();
        }

        // Buat tiket baru dengan foreign key yang benar
        $ticket = Ticket::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'account_id' => auth()->id(),
            'category_id' => $validated['category_id'],
            'priority_id' => $validated['priority_id'],
            'sbu_id' => $validated['sbu_id'],
            'department_id' => $validated['department_id'],
            'status_id' => $statusOpen->id,
        ]);

        // Jika ada file lampiran, simpan ke tabel ticket_attachments
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('ticket_attachments');
            $ticket->attachments()->create(['path' => $path]);
        }

        return redirect()->route('user.tickets.index')->with('success', 'Tiket berhasil dibuat.');
    }

    /**
     * Menampilkan detail sebuah tiket.
     */
    public function show(Ticket $ticket)
    {
        // Pastikan user hanya bisa melihat tiket miliknya sendiri
        if ($ticket->account_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke tiket ini.');
        }

        $ticket->load(['author', 'status', 'priority', 'category', 'sbu', 'department', 'comments.author', 'attachments']);

        return view('user.tickets.show', compact('ticket'));
    }

    /**
     * Menambahkan komentar baru ke tiket.
     */
    public function addComment(Request $request, Ticket $ticket)
    {
        // Pastikan user hanya bisa berkomentar di tiket miliknya sendiri
        if ($ticket->account_id !== auth()->id()) {
            abort(403);
        }

        $request->validate(['message' => 'required|string']);

        $ticket->comments()->create([
            'message' => $request->message,
            'account_id' => auth()->id(),
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}