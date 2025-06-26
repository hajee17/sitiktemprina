<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ClickUpApiService;
use App\Services\ClickUpSyncService;
use App\Models\Ticket;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CheckClickUpUpdates extends Command
{
    /**
     * Nama dan signature dari console command.
     *
     * @var string
     */
    protected $signature = 'clickup:check-updates';

    /**
     * Deskripsi console command.
     *
     * @var string
     */
    protected $description = 'Secara berkala memeriksa pembaruan tugas dari ClickUp dan menyinkronkannya ke database lokal.';

    protected $clickUpApi;
    protected $clickUpSync;

    /**
     * Buat instance command baru.
     * Laravel akan secara otomatis meng-inject service yang kita butuhkan.
     */
    public function __construct(ClickUpApiService $apiService, ClickUpSyncService $syncService)
    {
        parent::__construct();
        $this->clickUpApi = $apiService;
        $this->clickUpSync = $syncService;
    }

    /**
     * Jalankan console command.
     */
    public function handle()
    {
        $this->info("Memeriksa pembaruan dari ClickUp...");

        // 1. Dapatkan timestamp kapan terakhir kali kita cek.
        // Jika belum pernah, default-nya adalah 10 menit yang lalu.
        $lastCheckedTimestamp = Cache::get('clickup_last_checked_timestamp', now()->subMinutes(10)->timestamp * 1000);

        $listId = env('CLICKUP_DEFAULT_LIST_ID');
        if (!$listId) {
            $this->error("CLICKUP_DEFAULT_LIST_ID tidak diatur di file .env");
            return 1;
        }
        
        // 2. Panggil API untuk mendapatkan semua tugas yang diperbarui sejak pengecekan terakhir.
        $updatedTasks = $this->clickUpApi->getUpdatedTasks($listId, $lastCheckedTimestamp);

        if ($updatedTasks === null) {
            $this->error("Gagal mengambil data dari ClickUp API. Periksa log untuk detail.");
            return 1;
        }

        if (empty($updatedTasks)) {
            $this->info("Tidak ada pembaruan baru dari ClickUp.");
        } else {
            $this->info("Ditemukan " . count($updatedTasks) . " tugas yang diperbarui. Memulai sinkronisasi...");
            foreach ($updatedTasks as $task) {
                // Cari tiket lokal berdasarkan clickup_task_id
                $ticket = Ticket::where('clickup_task_id', $task['id'])->first();
                if ($ticket) {
                    $this->syncTaskData($ticket, $task);
                }
            }
            $this->info("Sinkronisasi selesai.");
        }
        
        // 3. Simpan timestamp saat ini (sedikit dikurangi untuk buffer) untuk pengecekan berikutnya.
        Cache::put('clickup_last_checked_timestamp', now()->subSeconds(30)->timestamp * 1000, now()->addDay());

        return 0;
    }

    /**
     * Logika untuk membandingkan dan menyinkronkan data satu tugas.
     */
    private function syncTaskData(Ticket $ticket, array $taskData)
    {
        $isUpdated = false;

        // --- Sinkronisasi Status ---
        if (isset($taskData['status']['status'])) {
            $newStatusName = $taskData['status']['status'];
            // Gunakan helper yang sudah kita buat di ClickUpSyncService
            $localStatusId = $this->clickUpSync->getStatusIdByName($newStatusName);
            
            if ($localStatusId !== null && $ticket->status_id != $localStatusId) {
                $ticket->status_id = $localStatusId;
                $isUpdated = true;
                Log::info("[Polling] Status tiket #{$ticket->id} diubah ke '{$newStatusName}'");
            }
        }
        
        // --- Anda bisa menambahkan logika sinkronisasi lain di sini (misal: prioritas, nama tugas, dll) ---

        if ($isUpdated) {
            $ticket->save();
        }
    }
}
