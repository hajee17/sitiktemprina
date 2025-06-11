<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Account;
use App\Models\KnowledgeBase;

class PageController extends Controller
{
    // Untuk halaman MyAccount.blade.php
    public function myAccount()
    {
        $user = Auth::user();
        return view('user.MyAccount', ['account' => $user]);
    }

    // Untuk halaman FAQ.blade.php
    public function faq()
    {
        // Logika untuk mengambil data FAQ jika dinamis
        return view('user.FAQ');
    }

    // Untuk halaman knowledgebase.blade.php
    public function knowledgeBase()
    {
        $knowledgeBases = KnowledgeBase::with('author', 'tags')->latest()->paginate(10);
        return view('user.knowledgebase', compact('knowledgeBases'));
    }

    // Untuk halaman knowledgebase-detail.blade.php
    public function knowledgeBaseDetail(KnowledgeBase $knowledge)
    {
        return view('user.knowledgebase-detail', compact('knowledge'));
    }
}