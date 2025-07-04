<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Services\ClickUpSyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncExistingTicketsToClickUp extends Command
{
    /**
     * Nama dan signature dari console command.
     * Anda akan menjalankan command ini dengan: php artisan clickup:sync-tickets
     *
     * @var string
     */
    protected $signature = 'clickup:sync-tickets';

    /**
     * Deskripsi dari console command.
     *
     * @var string
     */
    protected $description = 'Sync all existing local tickets that are not yet in ClickUp.';

    /**
     * @var ClickUpSyncService
     */
    protected $syncService;

    /**
     * Buat instance command baru.
     * Laravel akan secara otomatis meng-inject ClickUpSyncService ke sini.
     *
     * @param ClickUpSyncService $syncService
     */
    public function __construct(ClickUpSyncService $syncService)
    {
        parent::__construct();
        $this->syncService = $syncService;
    }

    /**
     * Jalankan console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Memulai proses sinkronisasi tiket ke ClickUp...');

        $ticketsToSync = Ticket::whereNull('clickup_task_id')->get();

        if ($ticketsToSync->isEmpty()) {
            $this->info('Tidak ada tiket baru untuk disinkronkan. Semuanya sudah up-to-date!');
            return 0;
        }

        $this->info("Ditemukan " . $ticketsToSync->count() . " tiket untuk disinkronkan.");

        $bar = $this->output->createProgressBar($ticketsToSync->count());
        $bar->start();

        $successCount = 0;
        $failCount = 0;

        foreach ($ticketsToSync as $ticket) {
            try {

                $success = $this->syncService->syncTicketToClickUp($ticket);

                if ($success) {
                    $successCount++;
                } else {
                    $failCount++;

                    $this->line("\n<error>Gagal menyinkronkan Tiket #{$ticket->id}: {$ticket->title}</error>");
                }
            } catch (\Exception $e) {
                $failCount++;
                $this->line("\n<error>Error saat menyinkronkan Tiket #{$ticket->id}: " . $e->getMessage() . "</error>");
                Log::error("Sync Command Error for Ticket #{$ticket->id}: " . $e->getMessage());
            }

            $bar->advance();

            usleep(200000);
        }

        $bar->finish();

        $this->info("\n\nProses sinkronisasi selesai.");
        $this->info("Berhasil: " . $successCount);
        $this->error("Gagal: " . $failCount);

        return 0;
    }
}
