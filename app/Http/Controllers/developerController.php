<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\Account;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
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
        // === 1. DATA UNTUK KARTU STATISTIK ===
        $statusOpenId = TicketStatus::where('name', 'Open')->value('id');
        $statusInProgressId = TicketStatus::where('name', 'In Progress')->value('id');
        $statusClosedId = TicketStatus::where('name', 'Closed')->value('id');
        $priorityHighId = TicketPriority::where('name', 'Tinggi')->value('id');

        $stats = [
            'highPriorityNew' => Ticket::where('status_id', $statusOpenId)->where('priority_id', $priorityHighId)->count(),
            'newTickets' => Ticket::where('status_id', $statusOpenId)->count(),
            'processedTickets' => Ticket::where('status_id', $statusInProgressId)->count(),
            'completedTickets' => Ticket::where('status_id', $statusClosedId)->count(),
            'totalTickets' => Ticket::count(),
        ];

        // === 2. DATA UNTUK GRAFIK MINGGUAN ===
        $weekStartDate = Carbon::now()->startOfWeek();
        $weekEndDate = Carbon::now()->endOfWeek();
        
        $ticketsByDay = Ticket::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$weekStartDate, $weekEndDate])
            ->groupBy('date')
            ->pluck('count', 'date');

        $chartData = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $weekStartDate->copy()->addDays($i);
            $chartData['weekDays'][] = $day->isoFormat('ddd');
            $chartData['weeklyData'][] = $ticketsByDay->get($day->toDateString(), 0);
        }
        
        // === 3. DATA UNTUK GRAFIK DISTRIBUSI STATUS ===
        $chartData['statusDistribution'] = Ticket::join('ticket_statuses', 'tickets.status_id', '=', 'ticket_statuses.id')
            ->select('ticket_statuses.name', DB::raw('count(tickets.id) as count'))
            ->groupBy('ticket_statuses.name')
            ->pluck('count', 'name')
            ->toArray();

        // === 4. DATA UNTUK TABEL TIKET TERBARU ===
        $latestTickets = Ticket::with('priority', 'status')->latest()->take(5)->get();

        // Menggabungkan semua data dan mengirimkannya ke view
        return view('developer.dashboard', array_merge($stats, $chartData, ['latestTickets' => $latestTickets]));
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
public function allTickets(Request $request)
{
    $statusOpenId = TicketStatus::where('name', 'Open')->value('id');
    $query = Ticket::with(['author.position', 'status', 'priority', 'category', 'sbu'])
                   ->where('status_id', $statusOpenId);

    // Memenuhi kebutuhan search
    if ($request->filled('search')) {
        $searchTerm = $request->input('search');
        $query->where(function($q) use ($searchTerm) {
            $q->where('id', 'like', '%' . $searchTerm . '%')
              ->orWhere('title', 'like', '%' . $searchTerm . '%')
              ->orWhereHas('author', fn($authorQuery) => $authorQuery->where('name', 'like', '%' . $searchTerm . '%'));
        });
    }

    // Memenuhi kebutuhan filter kategori
    if ($request->filled('category') && $request->input('category') !== 'Semua') {
        $categoryName = $request->input('category');
        $query->whereHas('category', fn($catQuery) => $catQuery->where('name', 'like', '%' . $categoryName . '%'));
    }
    
    $tickets = $query->latest()->paginate(15);
    
    // Memenuhi kebutuhan data kategori untuk tombol filter
    $categories = TicketCategory::all();
    
    // Mengirim semua data yang dibutuhkan ke view 'ambil-ticket'
    return view('developer.ambil-ticket', compact('tickets', 'categories'));
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
    public function myTicketsStatus()
{
    $tickets = Ticket::with([
            'author.position', // Eager load pembuat tiket dan posisinya
            'status', 
            'priority', 
            'category', 
            'sbu', 
            'attachments',
            'comments.author' // Eager load komentar dan penulis setiap komentar
        ])
        ->where('assignee_id', auth()->id()) // Query berdasarkan developer yang login
        ->whereHas('status', fn($q) => $q->where('name', '!=', 'Closed')) // Tampilkan yang belum selesai
        ->latest()
        ->paginate(5); // Paginasi, 5 tiket per halaman
            
    // Menggunakan nama file Anda: 'myticket.blade.php'
    return view('developer.myticket', compact('tickets'));
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

    public function updateProfile(Request $request)
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Pastikan email unik, TAPI abaikan email milik user itu sendiri
            'email' => ['required', 'email', Rule::unique('accounts')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            // Password hanya divalidasi jika diisi
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Siapkan data untuk diupdate
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ];

        // Jika user mengisi password baru, hash dan tambahkan ke data update
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        // Update data user
        $user->update($updateData);

        // Redirect kembali ke halaman profil dengan pesan sukses
        return redirect()->route('developer.profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }

        public function show(Ticket $ticket)
    {
        // Load semua relasi yang mungkin dibutuhkan di halaman detail
        $ticket->load(['author.position', 'author.department', 'status', 'priority', 'category', 'sbu', 'comments.author', 'attachments', 'assignee']);

        // Menggunakan nama file Anda: 'detail-ticket.blade.php'
        return view('developer.detail-ticket', compact('ticket'));
    }

    /**
     * Menampilkan form untuk mengedit tiket.
     */
    public function editTicket(Ticket $ticket)
    {
        // Ambil data yang mungkin dibutuhkan untuk dropdown di form edit
        $statuses = TicketStatus::all();
        $priorities = TicketPriority::all();
        $categories = TicketCategory::all();
        $developers = Account::whereHas('role', fn($q) => $q->where('name', 'developer'))->get();

        // Menggunakan nama file Anda: 'edit-myticket.blade.php'
        return view('developer.edit-myticket', compact('ticket', 'statuses', 'priorities', 'categories', 'developers'));
    }

    /**
     * Menampilkan form untuk mengedit akun.
     */
    public function editAkun(Account $account)
    {
        $roles = Role::all();
        
        // Menggunakan nama file Anda: 'edit-account.blade.php'
        return view('developer.edit-account', compact('account', 'roles'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|string|in:Diproses,Selesai',
        ]);

        // Cari ID status berdasarkan nama yang dikirim dari form
        $newStatus = TicketStatus::where('name', $request->status)->first();

        if ($newStatus && $ticket->status_id !== $newStatus->id) {
            $oldStatusName = $ticket->status->name;
            $ticket->status_id = $newStatus->id;
            $ticket->save();

            // Secara otomatis menambahkan komentar sebagai "Riwayat Penanganan"
            $ticket->comments()->create([
                'account_id' => auth()->id(),
                'message' => "Status tiket diubah dari '{$oldStatusName}' menjadi '{$newStatus->name}'."
            ]);

            return back()->with('success', 'Status tiket berhasil diperbarui.');
        }

        return back()->with('info', 'Tidak ada perubahan status.');
    }

    public function manageTickets(Request $request)
{
    // 1. Memenuhi kebutuhan statistik
    $stats = Ticket::join('ticket_statuses', 'tickets.status_id', '=', 'ticket_statuses.id')
        ->selectRaw('count(case when ticket_statuses.name = "Open" then 1 end) as tiket_baru')
        ->selectRaw('count(case when ticket_statuses.name = "In Progress" then 1 end) as tiket_diproses')
        ->selectRaw('count(case when ticket_statuses.name = "Closed" then 1 end) as tiket_selesai')
        ->selectRaw('count(*) as total_tiket')
        ->first()
        ->toArray();

    // 2. Memenuhi kebutuhan daftar tiket dengan relasi
    $query = Ticket::with(['priority', 'category', 'author', 'status', 'assignee'])->latest();

    $tickets = $query->paginate(10);
    
    // Ganti nama file view Anda dari 'kelola-ticktet.blade.php' menjadi 'kelola-ticket.blade.php' (tanpa typo)
    return view('developer.kelola-ticktet', compact('tickets', 'stats'));
}


/**
 * METODE BARU: Memperbarui tiket dari modal edit.
 */
public function updateTicket(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'status_id' => 'required|exists:ticket_statuses,id',
        ]);
        
        $ticket->update($validated);

        return redirect()->route('developer.manageTickets')->with('success', 'Tiket berhasil diperbarui.');
}

// Anda sudah punya metode destroyAkun, sekarang kita buat untuk tiket
public function destroyTicket(Ticket $ticket)
    {
        // Hapus lampiran terkait jika ada
        foreach ($ticket->attachments as $attachment) {
            Storage::delete($attachment->path);
            $attachment->delete();
        }
        
        // Hapus komentar terkait
        $ticket->comments()->delete();
        
        // Hapus tiket
        $ticket->delete();

        return back()->with('success', 'Tiket berhasil dihapus permanen.');
    }
        
}