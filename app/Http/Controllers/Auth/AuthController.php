<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
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
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Redirect berdasarkan role
            $user = Auth::user();
            if ($user->isDeveloper()) {
                return redirect()->intended(route('developer.dashboard'));
            }
            
            return redirect()->intended(route('user.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Menampilkan halaman registrasi.
     */
    public function showRegistrationForm()
    {
        return view('auth.Register');
    }

    /**
     * Menangani proses registrasi user baru.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Name' => ['required', 'string', 'max:255'],
            'Email' => ['required', 'string', 'email', 'max:255', 'unique:accounts,email'],
            'Telp_Num' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ]);

        if ($validator->fails()) {
            return redirect()->route('register')->withErrors($validator)->withInput();
        }

        $userRole = Role::where('name', 'user')->first();

        $account = Account::create([
            'name' => $request->Name,
            'username' => explode('@', $request->Email)[0] . rand(10,99), // Simple username generation
            'email' => $request->Email,
            'phone' => $request->Telp_Num,
            'password' => Hash::make($request->password),
            'role_id' => $userRole->id, //
        ]);

        Auth::login($account);

        return redirect()->route('user.dashboard');
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
    
    /**
     * Redirect ke Google untuk otentikasi.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Menangani callback dari Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $userRole = Role::where('name', 'user')->first();

            $account = Account::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'username' => str_replace(' ', '', strtolower($googleUser->getName())) . rand(10,99),
                    'password' => Hash::make(uniqid()), // Generate random password
                    'role_id' => $userRole->id,
                ]
            );

            Auth::login($account, true);
            return redirect()->route('user.dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['system' => 'Gagal melakukan otentikasi dengan Google.']);
        }
    }
}