<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBase;
use App\Models\KnowledgeTag;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class KnowledgeBaseController extends Controller
{

    public function index(Request $request)
    {
        $query = KnowledgeBase::with('author', 'tags')->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tag')) {
            $tag = $request->tag;
            $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('name', $tag);
            });
        }

        $knowledgeBases = $query->paginate(10);
        $tags = KnowledgeTag::all();

        return view('developer.knowledgebase', compact('knowledgeBases', 'tags'));
    }

    /**
     * Menampilkan form untuk membuat artikel baru.
     * Perlu view baru, misal: developer/knowledgebase/create.blade.php
     */
    public function create()
    {
        $tags = KnowledgeTag::all();
        return view('developer.knowledgebase-form', ['tags' => $tags]);
    }

    /**
     * Menyimpan artikel baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:blog,pdf,video',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:knowledge_tags,id',
            'content' => 'required_if:type,blog,video|nullable|string',
            'file_path' => 'required_if:type,pdf|nullable|file|mimes:pdf|max:10240', 
        ]);

        $data = $request->only('title', 'type');
        $data['account_id'] = Auth::id();
        $data['content'] = $request->content ?? '';

        if ($request->type === 'pdf') {
            if ($request->hasFile('file_path')) {

                $data['file_path'] = $request->file('file_path')->store('knowledge_files', 'public');
            }
        } else {
            $data['content'] = $request->content;
        }

        $knowledge = KnowledgeBase::create($data);

        if ($request->has('tags')) {
            $knowledge->tags()->sync($request->tags);
        }

        return redirect()->route('developer.knowledgebase.index')->with('success', 'Artikel berhasil dibuat.');
    }
    /**
     * Menampilkan form untuk mengedit artikel.
     * Perlu view baru, misal: developer/knowledgebase/edit.blade.php
     */
    public function edit(KnowledgeBase $knowledgebase)
    {
        if ($knowledgebase->source_ticket_id) {

            return redirect()
                ->route('developer.tickets.show', $knowledgebase->source_ticket_id)
                ->with('info', 'Artikel ini terhubung ke Tiket #' . $knowledgebase->source_ticket_id . '. Untuk memperbarui konten, silakan tambahkan komentar atau update tiket asli.');

        } else {

            $tags = KnowledgeTag::all();
            $knowledgebase->load('tags');

            return view('developer.knowledgebase-form', [
                'knowledge' => $knowledgebase,
                'tags' => $tags
            ]);
        }
    }

    /**
     * Mengupdate artikel di database.
     */
    public function update(Request $request, KnowledgeBase $knowledgebase)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:blog,pdf,video',
            'tags' => 'nullable|array',
            'content' => 'required_if:type,blog,video|nullable|string',
            'file_path' => [
                Rule::requiredIf(fn () => $request->type === 'pdf' && is_null($knowledgebase->file_path)),
                'nullable', 'file', 'mimes:pdf', 'max:10240',
            ],
        ]);

        $dataToUpdate = $request->only('title', 'type');

        if ($request->type === 'pdf') {
            $dataToUpdate['content'] = '';
            if ($request->hasFile('file_path')) {
                if ($knowledgebase->file_path) {
                    Storage::disk('public')->delete($knowledgebase->file_path);
                }
                $dataToUpdate['file_path'] = $request->file('file_path')->store('knowledge_files', 'public');
            }
        } else {
            $dataToUpdate['content'] = $request->content;
            if ($knowledgebase->file_path) {
                Storage::disk('public')->delete($knowledgebase->file_path);
                $dataToUpdate['file_path'] = null;
            }
        }

        $knowledgebase->update($dataToUpdate);
        $knowledgebase->tags()->sync($request->tags ?? []);

        return redirect()->route('developer.knowledgebase.index')->with('success', 'Artikel berhasil diperbarui.');
    }
    /**
     * Menghapus artikel dari database.
     */
    public function destroy(KnowledgeBase $knowledgebase)
    {
        $knowledgebase->delete();

        return redirect()->route('developer.knowledgebase.index')->with('success', 'Artikel berhasil dihapus.');
    }

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