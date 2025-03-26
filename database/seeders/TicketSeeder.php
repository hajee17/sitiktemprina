<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User;

class TicketSeeder extends Seeder
{
    public function run()
    {
        $user = User::first(); // Ambil user pertama dari database
        
        if (!$user) {
            $this->command->warn("⚠️ Tidak ada user di database! Harap buat user terlebih dahulu.");
            return;
        }

        Ticket::create([
            'code' => 'NET130325001',
            'user_id' => $user->id,
            'location' => 'Ruang Kantor B JPM',
            'category' => 'Jaringan',
            'description' => 'Sejak pagi ini, koneksi internet di ruang kantor B JPM sering terputus dan mengalami kecepatan yang sangat lambat.',
            'status' => 'Diverifikasi',
        ]);

        Ticket::create([
            'code' => 'NET130325002',
            'user_id' => $user->id,
            'location' => 'Ruang Server',
            'category' => 'Hardware',
            'description' => 'Server utama mengalami overheat dan restart secara otomatis setiap 30 menit.',
            'status' => 'Diproses',
        ]);

        Ticket::create([
            'code' => 'NET130325003',
            'user_id' => $user->id,
            'location' => 'Ruang Meeting',
            'category' => 'Software',
            'description' => 'Projector tidak bisa terhubung ke laptop meskipun kabel HDMI sudah diganti.',
            'status' => 'Selesai',
        ]);

        Ticket::create([
            'code' => 'NET130325004',
            'user_id' => $user->id,
            'location' => 'Lantai 3',
            'category' => 'Listrik',
            'description' => 'Salah satu stop kontak di lantai 3 mengeluarkan percikan api saat digunakan.',
            'status' => 'Dibatalkan',
        ]);

        $this->command->info("✅ 4 tiket contoh berhasil ditambahkan.");
    }
}