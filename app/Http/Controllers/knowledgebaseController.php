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
public function index(Request $request)
    {
        // Query dasar dengan relasi yang dibutuhkan
        $query = KnowledgeBase::with(['author', 'tags'])->latest();

        // 1. Memenuhi kebutuhan pencarian
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(fn($q) => 
                $q->where('title', 'like', "%{$searchTerm}%")
                ->orWhere('content', 'like', "%{$searchTerm}%")
            );
        }

        // 2. Memenuhi kebutuhan filter tag
        if ($request->filled('tag')) {
            $tag = $request->input('tag');
            $query->whereHas('tags', fn($q) => $q->where('name', $tag));
        }

        $knowledgeBases = $query->paginate(10);

        // 3. Memenuhi kebutuhan daftar tag untuk tombol filter
        $tags = KnowledgeTag::all();

        return view('developer.knowledgebase', compact('knowledgeBases', 'tags'));
    }
    /**
     * Menampilkan detail satu artikel knowledge base.
     */
    public function show(KnowledgeBase $knowledgeBase)
    {
        $knowledgeBase->load(['author', 'tags']);

        return view('developer.knowledgebase.show', compact('knowledgeBase'));
    }
public function publicShow(KnowledgeBase $knowledgeBase)
    {
        // Memuat relasi author yang dibutuhkan oleh view
        $knowledgeBase->load('author');

        // Mengganti nama variabel agar cocok dengan view ($knowledge)
        $knowledge = $knowledgeBase;

        // Menggunakan nama file Anda: 'knowledgebase-detail.blade.php'
        return view('user.knowledgebase-detail', compact('knowledge'));
    }

    public function publicIndex(Request $request)
    {
        // Query dasar dengan relasi yang dibutuhkan
        $query = KnowledgeBase::with(['author', 'tags'])->latest();

        // 1. Memenuhi kebutuhan pencarian
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('title', 'like', "%{$searchTerm}%");
        }

        // 2. Memenuhi kebutuhan filter tag
        if ($request->filled('tag') && $request->input('tag') !== 'Semua') {
            $tag = $request->input('tag');
            $query->whereHas('tags', fn($q) => $q->where('name', $tag));
        }

        $knowledgeBases = $query->paginate(12);

        // 3. Memenuhi kebutuhan daftar tag untuk tombol filter
        $tags = KnowledgeTag::all();
        
        // Menggunakan nama file Anda: 'knowledgebase.blade.php' di folder user
        return view('user.knowledgebase', compact('knowledgeBases', 'tags'));
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