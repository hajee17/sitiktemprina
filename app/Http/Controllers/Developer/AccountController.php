<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\Role; // Penting: Pastikan ini diimpor
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /**
     * Menampilkan halaman kelola akun. (kelola-akun.blade.php)
     */
    public function index(Request $request)
    {
        $userCounts = [
            'Developer' => Account::whereHas('role', function($query) {
                $query->where('name', 'developer'); 
            })->count(),
            'User' => Account::whereHas('role', function($query) {
                $query->where('name', 'user');    
            })->count(),
           
        ];
        $query = Account::with('role'); 

       
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%") // Pencarian nama
                  ->orWhere('email', 'ilike', "%{$search}%") // Pencarian email
                  ->orWhere('username', 'ilike', "%{$search}%"); // Pencarian username 
                if (is_numeric($search)) {
                    $q->orWhere('id', (int)$search);
                }
            });
        }

        // Logika Filter Role
        if ($request->filled('role_id') && $request->role_id !== null && $request->role_id !== '') {
            $query->where('role_id', $request->role_id);
        }

        // Mengambil data akun dengan paginasi dan menyertakan query string untuk filter
        $accounts = $query->latest()->paginate(10)->withQueryString();

        // Mengambil semua roles untuk dropdown filter di view
        $roles = Role::all();

        return view('developer.kelola-akun', compact('accounts', 'userCounts', 'roles'));
    }

    /**
     * Menyimpan user baru dari modal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Name' => 'required|string|max:255',
            'Email' => 'required|email|unique:accounts,email',
            'Telp_Num' => 'nullable|string|max:13', 
            'password' => 'required|string|min:8|confirmed',
            'ID_Role' => 'required|exists:roles,id',
        ]);

        Account::create([
            'name' => $request->Name,
            'username' => strtolower(explode('@', $request->Email)[0] . substr(uniqid(), -4)), 
            'email' => $request->Email,
            'phone' => $request->Telp_Num,
            'role_id' => $request->ID_Role,
            'password' => Hash::make($request->password), 
        ]);

        return back()->with('success', 'User baru berhasil ditambahkan.');
    }

    /**
     * Mengupdate akun dari inline edit (AJAX).
     */
    public function update(Request $request, Account $account) 
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('accounts', 'email')->ignore($account->id),
            ],
            'phone' => 'sometimes|nullable|string|max:13', 
            'ID_Role' => 'sometimes|required|exists:roles,id', 
        ]);

       
        $account->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'] ?? null, 
            'role_id' => $validatedData['ID_Role'], 
        ]);

        // Mengembalikan response JSON, memuat ulang relasi 'role' untuk data terbaru
        return response()->json([
            'success' => true,
            'message' => 'Akun berhasil diperbarui.',
            'data' => $account->load('role')
        ]);
    }

    /**
     * Hapus akun.
     */
    public function destroy(Account $account)
    {
        if ($account->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $account->delete();
        return back()->with('success', 'Akun berhasil dihapus.');
    }
}