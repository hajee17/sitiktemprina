<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PriorityPredictionService
{
    protected $config;

    public function __construct()
    {
        // Memuat konfigurasi bobot skor
        $this->config = config('priority');
    }

    /**
     * Memprediksi ID prioritas tiket berdasarkan data yang masuk.
     *
     * @param Request $request
     * @return int ID Prioritas (1: Tinggi, 2: Sedang, 3: Rendah)
     */
    public function predict(Request $request): int
    {
        $score = 0;
        $user = Auth::user();

        // 1. Hitung skor berdasarkan Jabatan/Posisi pengguna
        $positionId = $user->position_id ?? 1; // Default ke 'Staff' jika tidak ada
        $score += $this->config['positions'][$positionId] ?? 0;

        // 2. Hitung skor berdasarkan SBU
        $sbuId = $request->input('sbu_id');
        $score += $this->config['sbus'][$sbuId] ?? 0;

        // 3. Hitung skor berdasarkan Kata Kunci
        $text = strtolower($request->input('title') . ' ' . $request->input('description'));
        $keywordScore = 0;
        foreach ($this->config['keywords'] as $keyword => $value) {
            if (Str::contains($text, strtolower($keyword))) {
                // Ambil skor dari kata kunci paling penting yang ditemukan
                if ($value > $keywordScore) {
                    $keywordScore = $value;
                }
            }
        }
        $score += $keywordScore;

        // 4. Petakan skor total ke ID Prioritas
        if ($score >= $this->config['thresholds']['tinggi']) {
            return 1; // Tinggi
        } elseif ($score >= $this->config['thresholds']['sedang']) {
            return 2; // Sedang
        } else {
            return 3; // Rendah
        }
    }
}
