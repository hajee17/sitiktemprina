<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Account;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $loginData = [
            'Email' => $credentials['email'],
            'password' => $credentials['password'],
        ];

        if (Auth::attempt($loginData)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $user->updateLastLogin();

            if ($user->isDeveloper()) {
                return redirect()->route('developer.dashboard');
            }

            return redirect()->route('user.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Name' => 'required|string|max:255',
            'Email' => 'required|email|unique:account,Email',
            'Telp_Num' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $user = Account::create([
            'Name' => $request->Name,
            'Email' => $request->Email,
            'Telp_Num' => $request->Telp_Num,
            'password' => Hash::make($request->Password),
            'ID_Role' => $request->ID_Role ?? 2,
        ]);

        Auth::login($user);

        return redirect()->route('/')->with('success', 'Registrasi berhasil!');
    }
}
