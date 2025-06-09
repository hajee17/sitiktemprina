<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    /**
     * Menampilkan halaman utama (landing page) aplikasi.
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        // Mengembalikan view 'welcome.blade.php' atau halaman utama lainnya
        return view('welcome');
    }

    /**
     * Mengarahkan pengguna ke dashboard yang sesuai berdasarkan role mereka.
     * Ini adalah method yang dipanggil oleh rute '/dashboard' setelah login.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function dashboard()
    {
        // Pastikan user sudah login sebelum melanjutkan
        if (Auth::check()) {
            $user = Auth::user();

            // Gunakan helper method isDeveloper() yang sudah kita buat di model Account
            if ($user->isDeveloper()) {
                // Jika user adalah developer, arahkan ke rute dashboard developer
                return redirect()->route('developer.dashboard');
            }

            // Jika bukan developer (berarti user biasa), arahkan ke halaman daftar tiketnya
            return redirect()->route('user.tickets.index');
        }

        // Jika karena suatu alasan method ini diakses tanpa login,
        // kembalikan ke halaman login.
        return redirect()->route('login');
    }

    /**
     * Menampilkan halaman profil "Akun Saya" untuk pengguna yang sedang login.
     *
     * @return \Illuminate\View\View
     */
    public function myAccount()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        // Kirim data user ke view 'user.my-account.blade.php'
        return view('user.my-account', compact('user'));
    }

    /**
     * Menampilkan halaman Frequently Asked Questions (FAQ).
     *
     * @return \Illuminate\View\View
     */
    public function faq()
    {
        // Cukup tampilkan view statis 'pages.faq.blade.php'
        return view('pages.faq');
    }
}