<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBase;
use App\Models\KnowledgeTag;
use App\Models\Ticket;
use Illuminate\Http\Request;

class KnowledgeBaseController extends Controller
{
    /**
     * Menampilkan halaman daftar knowledge base.
     * View: user/knowledgebase.blade.php
     */
    public function index(Request $request)
    {
        $query = KnowledgeBase::with('author', 'tags')->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', $request->tag);
            });
        }

        $knowledgeBases = $query->paginate(10);
        $tags = KnowledgeTag::all();

        return view('user.knowledgebase', compact('knowledgeBases', 'tags'));
    }

    /**
     * Menampilkan halaman detail sebuah artikel knowledge base.
     * View: user/knowledgebase-detail.blade.php
     */
    public function show(KnowledgeBase $knowledgeBase)
{
    $sourceTicket = null;

    if ($knowledgeBase->source_ticket_id) {
        $sourceTicket = Ticket::with(['author', 'priority', 'status', 'category', 'sbu', 'department', 'attachments', 'comments.author'])
                              ->find($knowledgeBase->source_ticket_id);
    }

    return view('user.knowledgebase-detail', [
        'knowledge' => $knowledgeBase,
        'sourceTicket' => $sourceTicket,
    ]);
}
}