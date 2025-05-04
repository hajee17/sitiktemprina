<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class DeveloperController extends Controller
{
    public function __construct()
{
    $this->middleware('auth');
    $this->middleware('role:1');
    
    // Debug sementara
    if (Auth::check()) {
        dd(Auth::user()->ID_Role); // Harus mengembalikan 1
    }
}

    public function dashboard()
    {
        // Ticket counts with optimized queries
        $counts = $this->getTicketCounts();
        
        // Weekly ticket data
        $weeklyData = $this->getWeeklyTicketData();
        
        // Latest tickets with eager loading
        $latestTickets = Ticket::with(['status' => function($query) {
            $query->latest('Update_Time');
        }])
        ->latest()
        ->take(5)
        ->get();

        return view('developer.dashboard', array_merge($counts, $weeklyData, [
            'latestTickets' => $latestTickets
        ]));
    }

    protected function getTicketCounts()
    {
        return [
            'highPriorityNew' => Ticket::where('priority', 'Tinggi') // pastikan ini sesuai nama kolom sebenarnya
            ->whereHas('status', fn($q) => $q->where('Status', 'Baru'))
            ->count(),
                
            'newTickets' => Ticket::whereHas('status', fn($q) => $q->where('Status', 'Baru'))
                ->count(),
                
            'processedTickets' => Ticket::whereHas('status', fn($q) => $q->where('Status', 'Diproses'))
                ->count(),
                
            'completedTickets' => Ticket::whereHas('status', fn($q) => $q->where('Status', 'Selesai'))
                ->count(),
                
            'totalTickets' => Ticket::count(),
                
            'statusDistribution' => [
                'Baru' => Ticket::whereHas('status', fn($q) => $q->where('Status', 'Baru'))->count(),
                'Diproses' => Ticket::whereHas('status', fn($q) => $q->where('Status', 'Diproses'))->count(),
                'Selesai' => Ticket::whereHas('status', fn($q) => $q->where('Status', 'Selesai'))->count(),
            ]
        ];
    }

    protected function getWeeklyTicketData()
    {
        $weekStart = Carbon::now()->startOfWeek();
        $weekDays = [];
        $weeklyData = [];
        
        for ($i = 0; $i < 7; $i++) {
            $day = $weekStart->copy()->addDays($i);
            $weekDays[] = $day->isoFormat('ddd'); // Short day name (Sen, Sel, Rab, etc.)
            $weeklyData[] = Ticket::whereDate('created_at', $day)->count();
        }

        return [
            'weekDays' => $weekDays,
            'weeklyData' => $weeklyData
        ];
    }
    public function ambilTicket()
    {
        // Get unassigned tickets (status 'Baru')
        $tickets = Ticket::whereHas('status', function($query) {
                $query->where('Status', 'Baru');
            })
            ->with(['reporterUser']) // Assuming you have this relationship
            ->latest()
            ->get();

        return view('developer.ambil-ticket', compact('tickets'));
    }

    public function allTickets()
{
    $tickets = Ticket::with(['status' => function($query) {
        $query->latest('Update_Time');
    }, 'account'])->latest()->get();

    return view('developer.ambil-ticket', compact('tickets'));
}

    public function myticket()
    {
        // Ambil tiket yang sedang ditangani oleh developer yang login
        $tickets = Ticket::with(['status', 'account', 'documentations'])
            ->whereHas('status', function($query) {
                $query->where('ID_Account', auth()->user()->ID_Account)
                    ->where('Status', 'Diproses');
            })
            ->latest()
            ->get();

        return view('developer.myticket', compact('tickets'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Diproses,Selesai,Ditunda'
        ]);

        $ticket = Ticket::findOrFail($id);

        // Update status
        $ticket->status()->updateOrCreate(
            ['ID_Ticket' => $ticket->ID_Ticket],
            [
                'Status' => $request->status,
                'Update_Time' => now(),
                'Desc' => 'Status diupdate oleh developer',
                'ID_Account' => auth()->id()
            ]
        );

        return back()->with('success', 'Status tiket berhasil diperbarui');
    }



    // Then visit this URL to see your actual column names
    // Based on the result, update your kelolaAkun method manually:

    // 1. Tampilkan semua akun
    public function index()
    {
        $accounts = Account::orderBy('ID_Account', 'asc')->paginate(10);
        
        // Tambahkan perhitungan jumlah akun per role
        $userCounts = [
            'Developer' => Account::where('ID_Role', Account::ROLE_DEVELOPER)->count(),
            'User' => Account::where('ID_Role', Account::ROLE_USER)->count()
        ];
        
        return view('developer.kelola-akun', compact('accounts', 'userCounts'));
    }

    // 2. Update akun (nama, email, role)
    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'Name' => 'required|string|max:100',
            'Email' => 'required|email|unique:account,Email,'.$account->ID_Account.',ID_Account',
            'Telp_Num' => 'nullable|string|max:20',
            'ID_Role' => 'required|in:1,2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $account->update([
                'Name' => $request->Name,
                'Email' => $request->Email,
                'Telp_Num' => $request->Telp_Num,
                'ID_Role' => $request->ID_Role,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Perubahan berhasil disimpan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage()
            ], 500);
        }
    }

    // 3. Hapus akun
    public function destroy($id)
    {
            $account = Account::findOrFail($id);
            $account->delete();

            return response()->json(['message' => 'Akun berhasil dihapus']);
    }

    // 4. Tampilkan form tambah akun
    public function create()
    {
        return view('admin.kelola-akun.create');
    }

    // 5. Simpan akun baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:account,email',
            'password' => 'required|min:8|confirmed',
            'role_id'  => 'required|in:1,2',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Account::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role_id'  => $request->role_id,
        ]);

        return redirect()->route('developer.index')->with('success', 'Akun berhasil ditambahkan');
    }

}