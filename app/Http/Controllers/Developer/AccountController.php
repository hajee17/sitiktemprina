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
        // Statistik
        // PERBAIKAN DI SINI: Gunakan whereHas untuk menghitung berdasarkan nama role
        $userCounts = [
            'Developer' => Account::whereHas('role', function($query) {
                $query->where('name', 'developer'); // Pastikan 'Developer' sesuai dengan nama role di DB
            })->count(),
            'User' => Account::whereHas('role', function($query) {
                $query->where('name', 'user');     // Pastikan 'User' sesuai dengan nama role di DB
            })->count(),
            // Anda bisa menambahkan statistik lain jika ada
        ];
        $query = Account::with('role'); // Eager load relasi role

        // Logika Pencarian (menggunakan 'ilike' untuk case-insensitive)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%") // Pencarian nama
                  ->orWhere('email', 'ilike', "%{$search}%") // Pencarian email
                  ->orWhere('username', 'ilike', "%{$search}%"); // Pencarian username (jika ada)

                // Opsional: Jika Anda ingin mencari ID juga dan inputnya adalah numerik
                if (is_numeric($search)) {
                    $q->orWhere('id', (int)$search);
                }
            });
        }

        // Logika Filter Role
        // Memastikan role_id ada, tidak null, dan tidak kosong string (dari value="")
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
            'Telp_Num' => 'nullable|string|max:20', // Batasi panjang nomor telepon
            'password' => 'required|string|min:8|confirmed', // Menambahkan validasi password dan konfirmasi
            'ID_Role' => 'required|exists:roles,id',
        ]);

        Account::create([
            'name' => $request->Name,
            // Membuat username dari email, pastikan unik atau tambahkan logika lebih kuat
            'username' => strtolower(explode('@', $request->Email)[0] . substr(uniqid(), -4)), // Gunakan uniqid untuk keunikan
            'email' => $request->Email,
            'phone' => $request->Telp_Num,
            'role_id' => $request->ID_Role,
            'password' => Hash::make($request->password), // Hash password yang diinput user
        ]);

        return back()->with('success', 'User baru berhasil ditambahkan.');
    }

    /**
     * Mengupdate akun dari inline edit (AJAX).
     */
    public function update(Request $request, Account $account) // Menggunakan Route Model Binding
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                // Rule unique yang mengabaikan email akun yang sedang diupdate
                Rule::unique('accounts', 'email')->ignore($account->id),
            ],
            'phone' => 'sometimes|nullable|string|max:20', // Batasi panjang nomor telepon
            'ID_Role' => 'sometimes|required|exists:roles,id', // 'ID_Role' adalah nama input di frontend
        ]);

        // Perbaikan: gunakan nama kolom database yang benar untuk update
        $account->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'] ?? null, // Simpan null jika input phone kosong
            'role_id' => $validatedData['ID_Role'], // 'role_id' adalah kolom di database
        ]);

        // Mengembalikan response JSON, memuat ulang relasi 'role' untuk data terbaru
        return response()->json([
            'success' => true,
            'message' => 'Akun berhasil diperbarui.',
            'data' => $account->load('role') // Kirim kembali data akun yang sudah diupdate, termasuk nama role
        ]);
    }

    /**
     * Hapus akun.
     */
    public function destroy(Account $account)
    {
        // Pencegahan agar user tidak menghapus akunnya sendiri
        if ($account->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $account->delete();
        return back()->with('success', 'Akun berhasil dihapus.');
    }
}