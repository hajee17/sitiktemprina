<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\Department;
use App\Models\Position;
use App\Models\TicketCategory;
use App\Models\Sbu;
use App\Models\TicketAttachment;

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
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'sbu_id' => 'required|exists:sbus,id',
            'department_id' => 'required|exists:departments,id',
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:ticket_categories,id',
            'description' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048', // Validasi untuk setiap file
        ]);

        // Membuat tiket baru dengan data yang sudah tervalidasi
        $ticket = Ticket::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'account_id' => Auth::id(),
            'status_id' => 1, // Default status 'Open'
            'priority_id' => 3, // Default priority 'Rendah'
            'sbu_id' => $validatedData['sbu_id'],
            'department_id' => $validatedData['department_id'],
            'category_id' => $validatedData['category_id'],
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
    public function show(Ticket $ticket)
    {
        // Pastikan user hanya bisa melihat tiketnya sendiri
        if ($ticket->account_id !== Auth::id()) {
            abort(403);
        }
        return view('user.LacakTicket', compact('ticket'));
    }

    /**
     * Membatalkan tiket.
     */
    public function cancel(Request $request, Ticket $ticket)
    {
        if ($ticket->account_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['Desc' => 'required|string']);

        $ticket->status_id = 5; // Asumsi status 'Cancelled'
        // Anda mungkin ingin menyimpan alasan pembatalan di tabel comments
        // $ticket->comments()->create([...]);
        $ticket->save();
        
        return back()->with('success', 'Tiket berhasil dibatalkan.');
    }
}