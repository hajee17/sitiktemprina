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
use Illuminate\Support\Facades\Storage;
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
        $tickets = Ticket::with(['status', 'priority', 'category'])
            ->where('account_id', auth()->id())
            ->latest()
            ->paginate(10);

        // Menggunakan nama file Anda: 'MyTicket.blade.php'
        return view('user.MyTicket', compact('tickets'));
    }

    /**
     * Membatalkan (menghapus) tiket milik pengguna.
     */
    public function destroy(Ticket $ticket)
    {
        if ($ticket->account_id !== auth()->id()) {
            abort(403);
        }

        if ($ticket->status->name !== 'Open') {
            return back()->with('error', 'Tiket yang sudah diproses tidak dapat dibatalkan.');
        }

        foreach ($ticket->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->path);
        }
 
        $ticket->delete();

        return redirect()->route('user.tickets.index')->with('success', 'Tiket #' . $ticket->id . ' telah berhasil dibatalkan.');
    }

    /**
     * Menampilkan form untuk membuat tiket baru.
     */
    public function create()
{
    // Menyediakan data yang dibutuhkan untuk dropdown di form
    $categories = TicketCategory::orderBy('name')->get();
    $priorities = TicketPriority::orderBy('level')->get();

    // Menggunakan nama file Anda: 'createTicket.blade.php'
    return view('user.createTicket', compact('categories', 'priorities'));
}

public function store(Request $request)
    {
        // Validasi disesuaikan dengan input form yang baru dan standar
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:ticket_categories,id',
            'priority_id' => 'required|exists:ticket_priorities,id',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', // Maks 5MB
        ]);

        // Ambil status 'Open' sebagai status awal
        $statusOpen = TicketStatus::where('name', 'Open')->firstOrFail();
        $user = Auth::user();

        $ticket = Ticket::create([
            'title'         => $validated['title'],
            'description'   => $validated['description'],
            'category_id'   => $validated['category_id'],
            'priority_id'   => $validated['priority_id'],
            'account_id'    => $user->id,
            'status_id'     => $statusOpen->id,
            'sbu_id'        => $user->sbu_id, // Diambil otomatis dari profil user
            'department_id' => $user->department_id, // Diambil otomatis dari profil user
        ]);

        // Proses lampiran jika ada
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('ticket_attachments', 'public');
            $ticket->attachments()->create(['path' => $path]);
        }

        // Arahkan ke halaman daftar tiket milik user
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