<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Menampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Menangani proses login.
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'login' => 'required|string', // Bisa diisi email atau username
            'password' => 'required|string',
        ]);

        // 2. Tentukan tipe login (email atau username)
        $loginType = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // 3. Siapkan kredensial
        $credentials = [
            $loginType => $request->input('login'),
            'password' => $request->input('password'),
        ];

        // 4. Coba lakukan autentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Jika berhasil, regenerate session untuk keamanan
            $request->session()->regenerate();

            // Redirect ke halaman yang dituju sebelumnya atau ke dashboard
            return redirect()->intended(route('dashboard'));
        }

        // 5. Jika gagal, kembalikan error
        throw ValidationException::withMessages([
            'login' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ]);
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}