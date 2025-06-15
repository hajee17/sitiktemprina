<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Bobot Skor untuk Penentuan Prioritas Tiket Otomatis
    |--------------------------------------------------------------------------
    | Sesuaikan nilai skor di bawah ini untuk mengubah cara sistem
    | memprioritaskan tiket baru. Skor yang lebih tinggi berarti prioritas lebih tinggi.
    */

    // Bobot berdasarkan Jabatan/Posisi Pengguna
    // Kunci (key) adalah ID dari tabel 'positions'
    'positions' => [
        3 => 30, // Manager
        2 => 20, // Supervisor
        1 => 10, // Staff
    ],

    // Bobot berdasarkan Unit Bisnis Strategis (SBU)
    // Kunci (key) adalah ID dari tabel 'sbus'
    'sbus' => [
        2 => 40, // SBU Produksi (Sangat Kritis)
        1 => 25, // SBU Pengiriman (Penting)
        3 => 15, // SBU Pengemasan (Cukup Penting)
    ],

    // Bobot berdasarkan Kata Kunci di dalam judul atau deskripsi tiket
    // Sistem akan mencari kata kunci ini (tidak case-sensitive)
    'keywords' => [
        // Kritis Tinggi
        'mati' => 50,
        'down' => 50,
        'tidak bisa' => 50,
        'error' => 45,
        'server' => 45,
        'produksi' => 45, // Kata "produksi" sendiri sudah penting

        // Cukup Penting
        'lambat' => 30,
        'masalah' => 25,
        'jaringan' => 20,
        'printer' => 15,

        // Rendah
        'request' => 10,
        'pertanyaan' => 5,
        'instalasi' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Ambang Batas (Thresholds) Prioritas
    |--------------------------------------------------------------------------
    | Setelah skor total dihitung, skor tersebut akan dipetakan ke ID Prioritas
    | berdasarkan ambang batas ini.
    |
    | ID 1: Tinggi, ID 2: Sedang, ID 3: Rendah
    */
    'thresholds' => [
        'tinggi' => 70,  // Jika skor > 70, prioritasnya Tinggi
        'sedang' => 35,  // Jika skor > 35, prioritasnya Sedang
        // Jika tidak, prioritasnya Rendah
    ],
];
