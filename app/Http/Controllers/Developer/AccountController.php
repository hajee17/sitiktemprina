<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Account;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Menampilkan halaman kelola akun. (kelola-akun.blade.php)
     */
    public function index(Request $request)
    {
        // Statistik
        $userCounts = [
            'Developer' => Account::where('role_id', 1)->count(),
            'User' => Account::where('role_id', 2)->count(),
        ];
        
        $query = Account::with('role');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }
        
        $accounts = $query->paginate(10);
        
        return view('developer.kelola-akun', compact('accounts', 'userCounts'));
    }
    
    /**
     * Menyimpan user baru dari modal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Name' => 'required|string|max:255',
            'Email' => 'required|email|unique:accounts,email',
            'Telp_Num' => 'nullable|string',
            'ID_Role' => 'required|exists:roles,id',
        ]);
        
        Account::create([
            'name' => $request->Name,
            'email' => $request->Email,
            'phone' => $request->Telp_Num,
            'role_id' => $request->ID_Role,
            'password' => Hash::make( 'password' ), // Default password
        ]);
        
        return back()->with('success', 'User baru berhasil ditambahkan.');
    }

    /**
     * Mengupdate akun dari inline edit (AJAX).
     */
    public function update(Request $request, Account $account)
    {
        $data = $request->validate([
            'Name' => 'required|string|max:255',
            'Email' => 'required|email|unique:accounts,email,' . $account->id,
            'Telp_Num' => 'nullable|string',
            'ID_Role' => 'required|exists:roles,id',
        ]);
        
        $account->update([
            'name' => $data['Name'],
            'email' => $data['Email'],
            'phone' => $data['Telp_Num'],
            'role_id' => $data['ID_Role']
        ]);
        
        return response()->json(['success' => true, 'message' => 'Akun berhasil diperbarui.']);
    }

    /**
     * Hapus akun.
     */
    public function destroy(Account $account)
    {
        // Jangan biarkan user menghapus dirinya sendiri
        if ($account->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $account->delete();
        return back()->with('success', 'Akun berhasil dihapus.');
    }
}