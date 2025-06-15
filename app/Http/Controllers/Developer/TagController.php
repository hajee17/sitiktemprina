<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeTag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Menampilkan halaman manajemen tag.
     */
    public function index()
    {
        // Mengambil semua tag, diurutkan berdasarkan nama, beserta jumlah artikel yang menggunakannya.
        $tags = KnowledgeTag::withCount('knowledgeBases')->orderBy('name')->get();
        return view('developer.tags.index', compact('tags'));
    }

    /**
     * Menyimpan tag baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:knowledge_tags,name',
        ]);

        KnowledgeTag::create($request->only('name'));

        return back()->with('success', 'Tag baru berhasil ditambahkan.');
    }

    /**
     * Mengupdate tag yang sudah ada.
     */
    public function update(Request $request, KnowledgeTag $tag)
    {
        $request->validate([
            // Memastikan nama unik, kecuali untuk tag itu sendiri
            'name' => 'required|string|max:255|unique:knowledge_tags,name,' . $tag->id,
        ]);

        $tag->update($request->only('name'));

        return back()->with('success', 'Tag berhasil diperbarui.');
    }

    /**
     * Menghapus tag dari database.
     */
    public function destroy(KnowledgeTag $tag)
    {
        // Opsi: Cek jika tag masih digunakan sebelum dihapus
        if ($tag->knowledgeBases()->count() > 0) {
            return back()->with('error', 'Tag tidak dapat dihapus karena masih digunakan oleh artikel.');
        }

        $tag->delete();

        return back()->with('success', 'Tag berhasil dihapus.');
    }
}
