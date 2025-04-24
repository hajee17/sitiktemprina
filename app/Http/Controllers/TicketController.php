<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Status;
use App\Models\Chat;
use App\Models\Documentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    // Menampilkan daftar tiket pengguna
    public function myTickets()
{
    // Ambil ID_Account yang benar (bukan email)
    $accountId = Auth::user()->ID_Account;

    // Ambil semua tiket yang memiliki relasi status milik pengguna
    $tickets = Ticket::with('status')
        ->whereHas('status', function($query) use ($accountId) {
            $query->where('ID_Account', $accountId);
        })
        ->orderByDesc('ID_Ticket')
        ->paginate(10);

    return view('user/myticket', compact('tickets'));
}
    // Menampilkan form pembuatan tiket baru
    public function create()
    {
        return view('tickets.create');
    }

    // Menyimpan tiket baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Judul_Tiket' => 'required|string|max:255',
            'Category' => 'required|string|max:255',
            'Location' => 'required|string|max:255',
            'Desc' => 'required|string',
            'Attc' => 'nullable|file|max:2048',
        ]);

        // Simpan tiket
        $ticket = Ticket::create([
            'SBU' => $request->SBU ?? 'IT Support',
            'Dept' => $request->Dept ?? 'IT',
            'Position' => $request->Position ?? 'User',
            'Judul_Tiket' => $validated['Judul_Tiket'],
            'Category' => $validated['Category'],
            'Location' => $validated['Location'],
            'Desc' => $validated['Desc'],
            'Attc' => $request->hasFile('Attc') ? $request->file('Attc')->store('attachments') : null,
        ]);

        // Buat status awal
        Status::create([
            'ID_Ticket' => $ticket->ID_Ticket,
            'Update_Time' => now(),
            'Status' => 'Diverifikasi',
            'Desc' => 'Tiket baru telah dibuat',
            'Attc' => null
        ]);

        return redirect()->route('tickets.my')->with('success', 'Tiket berhasil dibuat!');
    }

    // Menampilkan detail tiket
    public function show($id)
    {
        $ticket = Ticket::with(['status', 'chats.account', 'documentations'])
            ->findOrFail($id);

        return view('tickets.show', compact('ticket'));
    }

    // Menambahkan chat ke tiket
    public function addChat(Request $request, $ticketId)
    {
        $validated = $request->validate([
            'Chat' => 'required|string|max:255',
        ]);

        Chat::create([
            'ID_Account' => Auth::id(),
            'ID_Ticket' => $ticketId,
            'Chat' => $validated['Chat']
        ]);

        return back()->with('success', 'Pesan berhasil ditambahkan');
    }

    // Menambahkan dokumentasi ke tiket
    public function addDocumentation(Request $request, $ticketId)
    {
        $validated = $request->validate([
            'Judul' => 'required|string|max:255',
            'Category' => 'required|string|max:255',
            'Desc' => 'required|string',
            'Text' => 'required|string',
            'Attc' => 'nullable|file|max:2048',
        ]);

        Documentation::create([
            'ID_Role' => Auth::user()->ID_Role,
            'ID_Ticket' => $ticketId,
            'Judul' => $validated['Judul'],
            'Category' => $validated['Category'],
            'Desc' => $validated['Desc'],
            'Text' => $validated['Text'],
            'Attc' => $request->hasFile('Attc') ? $request->file('Attc')->store('documentations') : null,
        ]);

        return back()->with('success', 'Dokumentasi berhasil ditambahkan');
    }

    // Mengupdate status tiket
    public function updateStatus(Request $request, $ticketId)
    {
        $validated = $request->validate([
            'Status' => 'required|string|max:255',
            'Desc' => 'required|string|max:255',
            'Attc' => 'nullable|file|max:2048',
        ]);

        Status::create([
            'ID_Ticket' => $ticketId,
            'Update_Time' => now(),
            'Status' => $validated['Status'],
            'Desc' => $validated['Desc'],
            'Attc' => $request->hasFile('Attc') ? $request->file('Attc')->store('status_attachments') : null,
        ]);

        return back()->with('success', 'Status tiket berhasil diperbarui');
    }

    

    public function __construct()
    {
    $this->middleware('auth');
    $this->middleware('role:2')->except(['developerActions']); // Hanya user biasa
    }   

    public function dashboard()
{
    $totalTiket = Ticket::count();
    $tiketDiproses = Status::where('Status', 'Diproses')->count();
    $tiketSelesai = Status::where('Status', 'Selesai')->count();

    return view('dashboard', compact('totalTiket', 'tiketDiproses', 'tiketSelesai'));
}
}