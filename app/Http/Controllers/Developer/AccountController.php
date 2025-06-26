<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
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

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%")
                  ->orWhere('username', 'ilike', "%{$search}%");
                if (is_numeric($search)) {
                    $q->orWhere('id', (int)$search);
                }
            });
        }

        if ($request->filled('role_id') && $request->role_id !== null && $request->role_id !== '') {
            $query->where('role_id', $request->role_id);
        }

        $accounts = $query->latest()->paginate(10)->withQueryString();

        $roles = Role::all();

        return view('developer.kelola-akun', compact('accounts', 'userCounts', 'roles'));
    }

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

        return response()->json([
            'success' => true,
            'message' => 'Akun berhasil diperbarui.',
            'data' => $account->load('role')
        ]);
    }

    public function destroy(Account $account)
    {
        if ($account->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $account->delete();
        return back()->with('success', 'Akun berhasil dihapus.');
    }
}