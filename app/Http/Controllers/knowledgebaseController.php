<?php

namespace App\Http\Controllers;

use App\Models\Documentation;
use App\Models\Ticket;

class KnowledgeBaseController extends Controller
{
    public function index()
    {
        $knowledgeBases = Documentation::with(['ticket', 'role'])
            ->whereNotNull('ID_Ticket')
            ->select('ID_Doc', 'Judul', 'Category', 'Desc', 'ID_Ticket', 'ID_Role')
            ->orderBy('ID_Doc', 'desc') // Urutkan berdasarkan ID_Doc sebagai gantinya
            ->get()
            ->map(function ($doc) {
                return (object)[
                    'judul' => $doc->Judul,
                    'kategori' => $doc->Category,
                    'kode_tiket' => $doc->ID_Ticket,
                    'author' => $doc->role->Role_Name ?? 'Unknown',
                    'tanggal' => $doc->ticket->created_at ?? now()
                ];
            });

        return view('developer.knowledgebase', compact('knowledgeBases'));
    }
        public function showAttachment($id)
        {
        $doc = Documentation::findOrFail($id);
        return response($doc->Attc)->header('Content-Type', 'application/pdf');
    }
}