<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Account;
use App\Models\Role;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validasi input 'login'
        $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        // 2. Tentukan tipe login (email atau username)
        $loginType = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // 3. Siapkan kredensial untuk dicoba
        $credentials = [
            $loginType => $request->input('login'),
            'password' => $request->input('password'),
        ];

        // 4. Lakukan proses login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->isdeveloper()) {
                return redirect()->route('developer.dashboard');
            }

            return redirect()->route('user.dashboard');
        }

        // 5. Jika gagal, kembalikan dengan pesan error
        return back()->withErrors([
            'login' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->withInput();
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showRegisterForm() 
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:accounts,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted',
        ]);

        $userRole = Role::where('name', 'user')->first();
        if (!$userRole) {
            return back()->withErrors(['msg' => 'Konfigurasi role sistem bermasalah.'])->withInput();
        }

        $user = Account::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'password' => Hash::make($validatedData['password']),
            'role_id' => $userRole->id, 
        ]);

        Auth::login($user);


        return redirect()->route('user.dashboard')->with('success', 'Registrasi berhasil!');
    }
}