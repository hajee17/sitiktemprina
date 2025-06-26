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
    public function myAccount()
    {
        $user = Auth::user();
        return view('user.MyAccount', ['account' => $user]);
    }

    public function faq(Request $request)
    {
        $query = KnowledgeBase::where('type', 'blog')
                    ->whereHas('tags', function ($q) {
                        $q->where('name', 'FAQ');
                    });

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        $faqs = $query->latest()->get();
        
        return view('user.FAQ', compact('faqs'));
    }

    public function knowledgeBase()
    {
        $knowledgeBases = KnowledgeBase::with('author', 'tags')->latest()->paginate(10);
        return view('user.knowledgebase', compact('knowledgeBases'));
    }

    public function knowledgeBaseDetail(KnowledgeBase $knowledge)
    {
        return view('user.knowledgebase-detail', compact('knowledge'));
    }

        public function showChangePasswordForm()
    {
        return view('user.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], 
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('user.account')->with('status', 'Kata sandi berhasil diperbarui.');
        }
    
}