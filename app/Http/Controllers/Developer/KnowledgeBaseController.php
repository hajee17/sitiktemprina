<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBase;
use App\Models\KnowledgeTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KnowledgeBaseController extends Controller
{
    /**
     * Menampilkan halaman utama manajemen knowledge base.
     * View: developer/knowledgebase.blade.php
     */
    public function index(Request $request)
    {
        $query = KnowledgeBase::with('author', 'tags')->latest();

        // Filter dan search jika ada
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $knowledgeBases = $query->paginate(10);
        
        return view('developer.knowledgebase', compact('knowledgeBases'));
    }

    /**
     * Menampilkan form untuk membuat artikel baru.
     * Perlu view baru, misal: developer/knowledgebase/create.blade.php
     */
    public function create()
    {
        $tags = KnowledgeTag::all();
        return view('developer.knowledgebase-form', ['tags' => $tags]); // Anda perlu membuat view ini
    }

    /**
     * Menyimpan artikel baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:blog,pdf,video', // Sesuaikan dengan tipe yang ada
            'tags' => 'nullable|array',
            'tags.*' => 'exists:knowledge_tags,id' // Validasi setiap tag
        ]);

        $knowledge = KnowledgeBase::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'account_id' => Auth::id(),
        ]);

        // Lampirkan tags ke artikel
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
        $tags = KnowledgeTag::all();
        // Pastikan relasi tags sudah di-load untuk form
        $knowledgebase->load('tags');
        
        // Menggunakan view form yang sama dengan create
        return view('developer.knowledgebase-form', ['knowledge' => $knowledgebase, 'tags' => $tags]);
    }

    /**
     * Mengupdate artikel di database.
     */
    public function update(Request $request, KnowledgeBase $knowledgebase)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:blog,pdf,video',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:knowledge_tags,id'
        ]);

        $knowledgebase->update($request->only('title', 'content', 'type'));

        // Sync tags, metode sync akan menangani penambahan/penghapusan secara otomatis
        $knowledgebase->tags()->sync($request->tags ?? []);

        return redirect()->route('developer.knowledgebase.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Menghapus artikel dari database.
     */
    public function destroy(KnowledgeBase $knowledgebase)
    {
        // Relasi pivot akan otomatis terhapus karena konfigurasi database
        $knowledgebase->delete();

        return redirect()->route('developer.knowledgebase.index')->with('success', 'Artikel berhasil dihapus.');
    }
}