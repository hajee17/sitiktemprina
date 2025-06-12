<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBase;
use App\Models\KnowledgeTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
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
            'type' => 'required|in:blog,pdf,video',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:knowledge_tags,id',
            // Validasi kondisional: 'content' wajib jika tipe adalah blog atau video
            'content' => 'required_if:type,blog,video|nullable|string',
            // Validasi kondisional: 'file_path' wajib jika tipe adalah pdf
            'file_path' => 'required_if:type,pdf|nullable|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        $data = $request->only('title', 'type');
        $data['account_id'] = Auth::id();
        $data['content'] = $request->content ?? '';

        if ($request->type === 'pdf') {
            if ($request->hasFile('file_path')) {
                // Simpan file ke storage/app/public/knowledge_files
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
        // Relasi pivot akan otomatis terhapus karena konfigurasi database
        $knowledgebase->delete();

        return redirect()->route('developer.knowledgebase.index')->with('success', 'Artikel berhasil dihapus.');
    }
}