<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBase;
use App\Models\KnowledgeTag; // <-- Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KnowledgeBaseController extends Controller
{
    /**
     * Menampilkan daftar semua artikel knowledge base.
     */
    public function index()
    {
        $knowledgeBases = KnowledgeBase::with(['author', 'tags'])->latest()->paginate(15);

        return view('developer.knowledgebase.index', compact('knowledgeBases'));
    }

    /**
     * Menampilkan detail satu artikel knowledge base.
     */
    public function show(KnowledgeBase $knowledgeBase)
    {
        $knowledgeBase->load(['author', 'tags']);

        return view('developer.knowledgebase.show', compact('knowledgeBase'));
    }

    /**
     * Menampilkan form untuk membuat artikel baru.
     */
    public function create()
    {

        $tags = KnowledgeTag::all();
        return view('developer.knowledgebase.create', compact('tags'));
    }

    /**
     * Menyimpan artikel baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|array',   
            'tags.*' => 'exists:knowledge_tags,id', 
        ]);

  
        $knowledgeBase = KnowledgeBase::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'account_id' => Auth::id(),
        ]);

        // Lampirkan (attach) tag yang dipilih ke artikel yang baru dibuat
        if (!empty($validated['tags'])) {
            $knowledgeBase->tags()->attach($validated['tags']);
        }

        return redirect()->route('knowledgebase.index')->with('success', 'Artikel berhasil dibuat.');
    }

    /**
     * Menampilkan form untuk mengedit artikel.
     */
    public function edit(KnowledgeBase $knowledgeBase)
    {
        $tags = KnowledgeTag::all();
        return view('developer.knowledgebase.edit', compact('knowledgeBase', 'tags'));
    }

    /**
     * Mengupdate artikel di database.
     */
    public function update(Request $request, KnowledgeBase $knowledgeBase)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:knowledge_tags,id',
        ]);

        $knowledgeBase->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        $knowledgeBase->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('knowledgebase.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Menghapus artikel dari database.
     */
    public function destroy(KnowledgeBase $knowledgeBase)
    {
        $knowledgeBase->delete();
        return redirect()->route('knowledgebase.index')->with('success', 'Artikel berhasil dihapus.');
    }
}