<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBase;
use App\Models\KnowledgeTag;
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

        // Logika untuk search
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Logika untuk filter by tag
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', $request->tag);
            });
        }

        $knowledgeBases = $query->paginate(10);
        $tags = KnowledgeTag::all(); // Untuk menampilkan tombol filter

        return view('user.knowledgebase', compact('knowledgeBases', 'tags'));
    }

    /**
     * Menampilkan halaman detail sebuah artikel knowledge base.
     * View: user/knowledgebase-detail.blade.php
     */
    public function show(KnowledgeBase $knowledgeBase)
    {
        // Laravel's Route Model Binding akan otomatis fetch data berdasarkan ID
        $knowledgeBase->load('author', 'tags'); // Eager load relasi

        return view('user.knowledgebase-detail', ['knowledge' => $knowledgeBase]);
    }
}