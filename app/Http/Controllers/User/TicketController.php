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
        $request->validate([
            'SBU' => 'required|exists:sbus,id',
            'Dept' => 'required|exists:departments,id',
            'Position' => 'required|exists:positions,id',
            'Judul_Tiket' => 'required|string|max:255',
            'Category' => 'required|exists:ticket_categories,id',
            'Location' => 'required|string|max:255',
            'Desc' => 'required|string',
            'Attc' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
        ]);
        
        // Buat tiket baru
        $ticket = Ticket::create([
            'title' => $request->Judul_Tiket,
            'description' => $request->Desc,
            'account_id' => Auth::id(),
            'status_id' => 1, // Default status 'Open'
            'category_id' => $request->Category,
            'priority_id' => 3, // Default priority 'Rendah'
            'sbu_id' => $request->SBU,
            'department_id' => $request->Dept,
            // 'position_id' tidak ada di tabel tickets, mungkin ini seharusnya ada di tabel accounts?
        ]);

        // Handle attachment
        if ($request->hasFile('Attc')) {
            $path = $request->file('Attc')->store('attachments', 'public');
            TicketAttachment::create([
                'ticket_id' => $ticket->id,
                'path' => $path,
            ]);
        }
        
        return redirect()->route('user.tickets.index')->with('success', 'Tiket berhasil dibuat!');
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