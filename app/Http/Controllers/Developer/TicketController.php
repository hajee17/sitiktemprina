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
use App\Services\ClickUpSyncService;

class TicketController extends Controller
{
    /**
     * Menampilkan tiket yang belum diambil. (ambil-ticket.blade.php)
     */
    public function index(Request $request)
    {
        $query = Ticket::whereNull('assignee_id')
                        ->where('status_id', 1) 
                        ->with(['author', 'priority', 'status', 'category', 'sbu', 'department']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%");
                if (is_numeric($search)) {
                    $q->orWhere('id', (int)$search);
                }
            });
        }

        if ($request->filled('priority_id')) {
            $query->where('priority_id', $request->priority_id);
        }

        $tickets = $query->latest()->paginate(9)->withQueryString();
        $priorities = TicketPriority::all();

        return view('developer.ambil-ticket', compact('tickets', 'priorities'));
    }

    public function show(Ticket $ticket)
    {
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
            $ticket->status_id = 2;
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
                        ->where('status_id', '!=', 4);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%");
                if (is_numeric($search)) {
                    $q->orWhere('id', (int)$search);
                }
            });
        }

        if ($request->filled('priority_id')) {
            $query->where('priority_id', $request->priority_id);
        }

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        $tickets = $query->latest()->paginate(10)->withQueryString();

        $priorities = TicketPriority::all();
        $statuses = TicketStatus::where('name', '!=', 'Open')->get();

        return view('developer.myticket', compact('tickets', 'priorities', 'statuses'));
    }

    /**
     * Menampilkan halaman kelola semua tiket. (kelola-ticket.blade.php)
     */
    public function manageAll(Request $request)
    {
        $stats = [
            'total_tickets' => Ticket::count(),
            'open' => Ticket::where('status_id', 1)->count(),
            'in_progress' => Ticket::where('status_id', 2)->count(),
            'closed' => Ticket::where('status_id', 4)->count(),
        ];

        $query = Ticket::with(['author', 'assignee', 'priority', 'status', 'category']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                  ->orWhereHas('author', function($q_author) use ($search) {
                      $q_author->where('name', 'ilike', "%{$search}%");
                  });
                if (is_numeric($search)) {
                    $q->orWhere('id', (int)$search);
                }
            });
        }

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        if ($request->filled('priority_id')) {
            $query->where('priority_id', $request->priority_id);
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();

        $statuses = TicketStatus::all();
        $priorities = TicketPriority::all();
        return view('developer.kelola-ticket', compact('tickets', 'stats', 'statuses', 'priorities'));
    }

    /**
     * Update tiket dari modal.
     */
    public function update(Request $request, Ticket $ticket, ClickUpSyncService $clickUpSyncService)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'status_id' => 'required|exists:ticket_statuses,id',
            'comment' => 'nullable|string',
            'comment_file' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf|max:5120',
            'create_knowledge_base' => 'nullable|boolean',
        ]);

        if ($request->filled('comment') || $request->hasFile('comment_file')) {
            $filePath = null;
            if ($request->hasFile('comment_file')) {
                $filePath = $request->file('comment_file')->store('ticket_comments', 'public');
            }
            $ticket->comments()->create([
                'account_id' => Auth::id(),
                'message' => $validated['comment'] ?? '',
                'file_path' => $filePath,
            ]);
            $comment='hello';
            $clickUpSyncService->addCommentToClickUpTask($comment);
        }

        $ticket->status_id = $validated['status_id'];
        $ticket->save();
        if ($ticket->clickup_task_id || $oldStatusId !== $ticket->status_id) {
             $clickUpSyncService->syncTicketToClickUp($ticket);
        }

        $isClosing = \App\Models\TicketStatus::find($validated['status_id'])->name === 'Closed';

        if ($isClosing && $request->boolean('create_knowledge_base')) {
            if (!$ticket->knowledgeBaseArticle()->exists()) {
                $solutionContent = $validated['comment'] ?? $ticket->description;
                if (!empty(trim($solutionContent))) {
                    $tag = KnowledgeTag::firstOrCreate(['name' => $ticket->category->name]);

                    $kb = KnowledgeBase::create([
                        'title' => $ticket->title,
                        'content' => $solutionContent,
                        'type' => 'blog',
                        'account_id' => Auth::id(),
                        'source_ticket_id' => $ticket->id,
                    ]);

                    $kb->tags()->sync([$tag->id]);
                }
            }
        }

        if ($isClosing) {
            return redirect()->route('developer.myticket')
                             ->with('success', 'Tiket #' . $ticket->id . ' telah berhasil ditutup.');
        }

        return redirect()->route('developer.tickets.show', $ticket->id)
                         ->with('success', 'Tiket berhasil diperbarui.');

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