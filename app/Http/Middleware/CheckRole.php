<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role The role required to access the route.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Pastikan pengguna sudah login. Jika tidak, middleware 'auth' akan menanganinya,
        //    tapi ini adalah lapisan keamanan tambahan yang baik.
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Ambil data pengguna yang sedang login.
        $user = Auth::user();

        // 3. Periksa apakah pengguna memiliki relasi 'role' dan namanya cocok
        //    dengan peran yang dibutuhkan oleh rute ($role).
        //    Misalnya, jika rute butuh 'developer', maka $user->role->name harus 'developer'.
        if ($user->role && $user->role->name === $role) {
            // 4. Jika peran cocok, izinkan permintaan untuk melanjutkan ke controller.
            return $next($request);
        }

        // 5. Jika peran tidak cocok, hentikan permintaan dan tampilkan halaman 403 Forbidden.
        //    Ini adalah sumber dari error "Forbidden" yang Anda lihat, dan ini perilaku yang benar.
        abort(403, 'AKSES DITOLAK. ANDA TIDAK MEMILIKI HAK AKSES UNTUK HALAMAN INI.');
    }
}
