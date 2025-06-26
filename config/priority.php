<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Bobot Skor untuk Penentuan Prioritas Tiket Otomatis
    |--------------------------------------------------------------------------
    | Sesuaikan nilai skor di bawah ini untuk mengubah cara sistem
    | memprioritaskan tiket baru. Skor yang lebih tinggi berarti prioritas lebih tinggi.
    */

    'positions' => [
        3 => 30, // Manager
        2 => 20, // Supervisor
        1 => 10, // Staff
    ],

    'sbus' => [
        2 => 40, // SBU Produksi (Sangat Kritis)
        1 => 25, // SBU Pengiriman (Penting)
        3 => 15, // SBU Pengemasan (Cukup Penting)
    ],


    'keywords' => [
        // Kritis Tinggi
        'mati' => 50,
        'down' => 50,
        'tidak bisa' => 50,
        'error' => 45,
        'server' => 45,
        'produksi' => 45,

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
        'tinggi' => 70,
        'sedang' => 35,
    ],
];
