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
        $positionId = $user->position_id ?? 1; 
        $score += $this->config['positions'][$positionId] ?? 0;
        $sbuId = $request->input('sbu_id');
        $score += $this->config['sbus'][$sbuId] ?? 0;
        $text = strtolower($request->input('title') . ' ' . $request->input('description'));
        $keywordScore = 0;
        foreach ($this->config['keywords'] as $keyword => $value) {
            if (Str::contains($text, strtolower($keyword))) {
                if ($value > $keywordScore) {
                    $keywordScore = $value;
                }
            }
        }
        $score += $keywordScore;

        if ($score >= $this->config['thresholds']['tinggi']) {
            return 1;
        } elseif ($score >= $this->config['thresholds']['sedang']) {
            return 2;
        } else {
            return 3;=
        }
    }
}
