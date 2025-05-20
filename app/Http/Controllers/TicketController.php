<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Status;
use App\Models\Chat;
use App\Models\Documentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
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

        // Ambil prefix dari Category (tiga huruf pertama)
        $categoryPrefix = strtoupper(substr($validated['Category'], 0, 3));

        // Ambil tanggal, bulan, dan tahun saat ini
        $datePrefix = now()->format('dmy'); // Format: ddmmyy

        // Ambil nomor urut tiket yang sudah ada pada tanggal yang sama
        $ticketCount = Ticket::whereDate('created_at', now()->toDateString())->count();
        $ticketNumber = str_pad($ticketCount + 1, 2, '0', STR_PAD_LEFT); // Tambahkan nomor urut, padding dua digit

        // Gabungkan menjadi ID_Ticket
        $ticketId = $categoryPrefix . $datePrefix . $ticketNumber;
        \Log::info("Generated Ticket ID: " . $ticketId);  // Log for debugging
        // Simpan tiket
        $ticket = Ticket::create([
            'ID_Ticket' => $ticketId,
            'SBU' => $request->SBU ?? 'IT Support',
            'Dept' => $request->Dept ?? 'IT',
            'Position' => $request->Position ?? 'User',
            'Judul_Tiket' => $validated['Judul_Tiket'],
            'Category' => $validated['Category'],
            'Location' => $validated['Location'],
            'Desc' => $validated['Desc'],
            'Attc' => $request->hasFile('Attc') ? $request->file('Attc')->store('attachments') : null,
            'ID_Account' => Auth::user()->ID_Account,
        ]);

        // Buat status awal
        Status::create([
            'ID_Ticket' => $ticket->ID_Ticket,
            'Update_Time' => now(),
            'Status' => 'Diverifikasi',
            'Desc' => 'Tiket baru telah dibuat',
            'Attc' => null
        ]);

        return redirect()->route('user.createticketdone')->with('success', 'Tiket berhasil dibuat!');
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
            'SBU' => 'required|string|max:255',
            'Dept' => 'required|string|max:255',
            'Position' => 'required|string|max:255',
            'Judul_Tiket' => 'required|string|max:255',
            'Category' => 'required|string|max:255',
            'Location' => 'required|string|max:255',
            'Desc' => 'required|string',
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
            'ID_Account' => Auth::user()->ID_Account,
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

    public function createticket()
    {
    $sbus = ['IT Support', 'Finance'];
    $departments = ['IT', 'HR'];
    $positions = ['Manager', 'Staff'];
    $categories = ['Bug', 'Request'];

    return view('user.createticket', compact('sbus', 'departments', 'positions', 'categories'));
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

    return view('user.dashboard', compact('totalTiket', 'tiketDiproses', 'tiketSelesai'));
}

public function take($id)
{
    // Cari tiket
    $ticket = Ticket::findOrFail($id);
    
    // Validasi bahwa tiket belum diambil
    if ($ticket->status->Status !== 'Baru') {
        return back()->with('error', 'Tiket sudah diambil oleh orang lain');
    }
    
    // Update status tiket
    $ticket->status()->updateOrCreate(
        ['ID_Ticket' => $ticket->ID_Ticket],
        [
            'Status' => 'Diproses',
            'Update_Time' => now(),
            'Desc' => 'Tiket diambil oleh developer',
            'ID_Account' => auth()->id() // ID developer yang mengambil
        ]
    );
    
    return redirect()->route('developer.tickets.index')
        ->with('success', 'Tiket berhasil diambil');
}

public function index()
{
    $tickets = Ticket::latest()->paginate(10);
    $stats = [
        'prioritas_tinggi' => Ticket::where('prioritas', 'Tinggi')->count(),
        'tiket_baru' => Ticket::where('status', 'Baru')->count(),
        'tiket_diproses' => Ticket::where('status', 'Diproses')->count(),
        'tiket_selesai' => Ticket::where('status', 'Selesai')->count(),
        'total_tiket' => Ticket::count(),
    ];
    return view('developer.kelola-tiket', compact('tickets', 'stats'));
}

    public function showdev($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('developer.kelola-tiket.show', compact('ticket'));
    }

    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('developer.kelola-tiket.edit', compact('ticket'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string',
            'status' => 'required|string',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update($request->only(['judul', 'status']));
        return redirect()->route('developer.tickets.index')->with('success', 'Tiket berhasil diperbarui');
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return redirect()->back()->with('success', 'Tiket berhasil dihapus');
    }
}