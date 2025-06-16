<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\TicketStatus;
use App\Models\Department;
use App\Models\Position;
use App\Models\TicketCategory;
use App\Models\Sbu;
use App\Models\TicketAttachment;
use App\Services\PriorityPredictionService;
use App\Services\KnowledgeBaseRecommenderService;

class TicketController extends Controller
{
    /**
     * Menampilkan daftar tiket milik user. (MyTicket.blade.php)
     */
    public function index()
    {
        $tickets = Ticket::where('account_id', Auth::id())
            ->with(['status', 'category'])
            ->latest()
            ->paginate(10);
            
        return view('user.MyTicket', compact('tickets'));
    }

    /**
     * Menampilkan form untuk membuat tiket baru. (createTicket.blade.php)
     */
    public function create()
    {
        // Ambil data untuk dropdown dari database
        $departments = Department::pluck('name', 'id');
        $positions = Position::pluck('name', 'id');
        $categories = TicketCategory::pluck('name', 'id');
        $sbus = Sbu::pluck('name', 'id');

        return view('user.createTicket', compact('departments', 'positions', 'categories', 'sbus'));
    }

    /**
     * Menyimpan tiket baru ke database.
     */
    public function store(Request $request, PriorityPredictionService $priorityService)
    {
        $validatedData = $request->validate([
            'sbu_id' => 'required|exists:sbus,id',
            'department_id' => 'required|exists:departments,id',
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:ticket_categories,id',
            'description' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
        ]);
        
        $thumbnailPath = null;
        if ($request->hasFile('attachments')) {
            $firstFile = $request->file('attachments')[0];
 
            $thumbnailPath = $firstFile->store('tickets/thumbnails', 'public');
        }

        $predictedPriorityId = $priorityService->predict($request);

        $ticket = Ticket::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'account_id' => Auth::id(),
            'status_id' => 1,
            'sbu_id' => $validatedData['sbu_id'],
            'department_id' => $validatedData['department_id'],
            'category_id' => $validatedData['category_id'],
            'priority_id' => $predictedPriorityId, // <-- Menggunakan hasil prediksi
        ]);

        // Menangani upload file lampiran jika ada
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                // Simpan file ke storage/app/public/attachments
                $path = $file->store('attachments', 'public');
                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'path' => $path,
                ]);
            }
        }
        
        return redirect()->route('user.tickets.index')->with('success', 'Tiket Anda berhasil dibuat!');
    }

    /**
     * Menampilkan detail tiket. (LacakTicket.blade.php)
     */
    public function show(Ticket $ticket, KnowledgeBaseRecommenderService $recommender)
    {
        if ($ticket->account_id !== Auth::id() && !Auth::user()->isDeveloper()) {
            abort(403, 'Akses Ditolak');
        }
        
        // Memuat relasi yang diperlukan untuk tiket
        $ticket->load('status', 'category', 'priority', 'attachments', 'author', 'comments.author');
        
        // MEMANGGIL MODEL: Dapatkan rekomendasi artikel
        $recommendations = $recommender->getRecommendations($ticket);
        
        // Kirim data tiket dan rekomendasi ke view
        return view('user.LacakTicket', compact('ticket', 'recommendations'));
    }


    /**
     * Membatalkan tiket.
     */
    public function cancel(Request $request, Ticket $ticket)
    {
        if ($ticket->account_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk tiket ini.');
        }

        $ticket->delete();
        
        return redirect()->route('user.tickets.index')->with('success', 'Tiket berhasil dihapus secara permanen.');
    }
}