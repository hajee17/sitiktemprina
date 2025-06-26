<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClickUpSyncService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ClickUpWebhookController extends Controller
{
    /**
     * @var ClickUpSyncService
     */
    protected $syncService;

    public function __construct(ClickUpSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    public function handle(Request $request)
    {
        // ====================================================================
        // PERINGATAN: Validasi Keamanan Dinonaktifkan
        // Kode validasi signature di bawah ini telah di-comment-out.
        // Ini dilakukan karena kemungkinan Secret Key tidak tersedia pada paket Anda.
        // Lanjutkan dengan hati-hati.
        // ====================================================================

        /*
        // Kode validasi yang dinonaktifkan:
        $secret = env('CLICKUP_WEBHOOK_SECRET');
        $signature = $request->header('X-Signature');

        if (empty($secret) || empty($signature)) {
            Log::warning('ClickUp Webhook: Request ditolak karena secret atau signature tidak ada.');
            return response()->json(['error' => 'Unauthorized. Missing secret or signature.'], 401);
        }

        $calculatedSignature = hash_hmac('sha256', $request->getContent(), $secret);

        if (!hash_equals($calculatedSignature, $signature)) {
            Log::error('ClickUp Webhook: Signature tidak valid.', ['request_signature' => $signature]);
            return response()->json(['error' => 'Invalid signature.'], 401);
        }
        */

        // Langsung proses payload tanpa validasi signature
        $payload = $request->all();
        Log::info('ClickUp Webhook: Menerima payload (validasi dilewati).', $payload);

        try {
            // Panggil service untuk memproses data
            $this->syncService->syncClickUpToLocal($payload);
        } catch (\Exception $e) {
            Log::error('ClickUp Webhook: Gagal memproses payload.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Internal Server Error while processing payload.'], 500);
        }

        // Kirim response 200 OK
        return response()->json(['status' => 'success'], 200);
    }
}
