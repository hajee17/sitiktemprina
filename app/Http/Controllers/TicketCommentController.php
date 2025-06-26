<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ClickUpSyncService;

class TicketCommentController extends Controller
{
    /**
     * Menyimpan komentar baru pada sebuah tiket.
     */
    public function store(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required_without:comment_file|nullable|string',
            'comment_file' => 'required_without:message|nullable|file|mimes:jpg,jpeg,png,gif|max:5120', // Max 5MB
        ]);

        $filePath = null;
        if ($request->hasFile('comment_file')) {
            $filePath = $request->file('comment_file')->store('ticket_comments', 'public');
        }

        $ticket->comments()->create([
            'account_id' => Auth::id(),
            'message' => $request->message ?? '',
            'file_path' => $filePath,
        ]);
        $clickUpSyncService->addCommentToClickUpTask($comment);
        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}
