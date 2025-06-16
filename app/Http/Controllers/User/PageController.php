<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Account;
use App\Models\KnowledgeBase;
use Illuminate\Support\Facades\Hash;

class PageController extends Controller
{
    // Untuk halaman MyAccount.blade.php
    public function myAccount()
    {
        $user = Auth::user();
        return view('user.MyAccount', ['account' => $user]);
    }

    // Untuk halaman FAQ.blade.php
    public function faq(Request $request)
    {
        $query = KnowledgeBase::where('type', 'blog')
                    ->whereHas('tags', function ($q) {
                        // Mengasumsikan Anda memiliki tag bernama 'FAQ'
                        $q->where('name', 'FAQ');
                    });

        // Menambahkan fungsionalitas pencarian
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        $faqs = $query->latest()->get();
        
        return view('user.FAQ', compact('faqs'));
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

        public function showChangePasswordForm()
    {
        return view('user.change-password');
    }

    // Menangani proses ubah kata sandi
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // min:8 atau sesuai kebijakan Anda
        ]);

        $user = Auth::user();

        // Verifikasi kata sandi saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
        }

        // Update kata sandi
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('user.account')->with('status', 'Kata sandi berhasil diperbarui.');
        }
    
}