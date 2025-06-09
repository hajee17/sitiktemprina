<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
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
    public function trackTicket(Request $request)
{
    $request->validate(['ticket_id' => 'required|string']);

    $ticketId = $request->ticket_id;
    
    // Cari tiket berdasarkan ID
    $ticket = Ticket::find($ticketId);

    // Jika tiket tidak ditemukan atau user tidak berhak melihatnya
    if (!$ticket || $ticket->account_id !== Auth::id()) {
        return redirect()->route('dashboard')->with('error', 'Tiket tidak ditemukan atau Anda tidak memiliki akses.');
    }

    // Jika tiket ditemukan, arahkan ke halaman detailnya
    return redirect()->route('user.tickets.show', $ticket->id);
}
}