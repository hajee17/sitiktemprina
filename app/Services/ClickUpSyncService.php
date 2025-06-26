<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\KnowledgeBase;
use App\Models\TicketComment;
use App\Models\Account; // Pastikan model Account di-import
use Illuminate\Support\Facades\Log;

class ClickUpSyncService
{
    protected $clickUpApi;
    protected $defaultClickUpListId;
    protected $clickUpStatusMapping;
    protected $clickUpPriorityMapping;

    public function __construct(ClickUpApiService $clickUpApi)
    {
        $this->clickUpApi = $clickUpApi;
        $this->defaultClickUpListId = env('CLICKUP_DEFAULT_LIST_ID');

        // Mapping status dari sistem Anda ke ClickUp
        $this->clickUpStatusMapping = [
            1 => 'open',
            2 => 'in progress',
            3 => 'on hold',
            4 => 'closed',
        ];

        // Mapping prioritas dari sistem Anda ke nilai numerik ClickUp
        $this->clickUpPriorityMapping = [
            1 => 2, // 'Tinggi' -> High (2)
            2 => 3, // 'Sedang' -> Normal (3)
            3 => 4, // 'Rendah' -> Low (4)
        ];
    }

    public function syncTicketToClickUp(Ticket $ticket)
    {
        if ($ticket->clickup_task_id) {
            return $this->updateClickUpTask($ticket);
        }
        return $this->createClickUpTask($ticket);
    }

    protected function createClickUpTask(Ticket $ticket)
    {
        $taskData = [
            'name' => $ticket->title,
            'description' => $ticket->description . "\n\n--- Ticket Source: " . route('user.tickets.show', $ticket->id),
            'status' => $this->clickUpStatusMapping[$ticket->status_id] ?? 'open',
            'priority' => $this->clickUpPriorityMapping[$ticket->priority_id] ?? 3,
            'tags' => [optional($ticket->category)->name],
        ];

        $response = $this->clickUpApi->createTask($this->defaultClickUpListId, $taskData);

        if ($response && isset($response['id'])) {
            $ticket->clickup_task_id = $response['id'];
            $ticket->save();
            Log::info("Ticket #{$ticket->id} created in ClickUp as Task ID: {$response['id']}");
            return true;
        }

        Log::error("Failed to create ClickUp task for Ticket #{$ticket->id}");
        return false;
    }

    protected function updateClickUpTask(Ticket $ticket)
    {
        $taskData = [
            'name' => $ticket->title,
            'description' => $ticket->description . "\n\n--- Ticket Source: " . route('user.tickets.show', $ticket->id),
            'status' => $this->clickUpStatusMapping[$ticket->status_id] ?? 'open',
            'priority' => $this->clickUpPriorityMapping[$ticket->priority_id] ?? 3,
        ];

        $response = $this->clickUpApi->updateTask($ticket->clickup_task_id, $taskData);

        if ($response) {
            Log::info("Ticket #{$ticket->id} (ClickUp Task ID: {$ticket->clickup_task_id}) updated in ClickUp.");
            return true;
        }

        Log::error("Failed to update ClickUp task {$ticket->clickup_task_id} for Ticket #{$ticket->id}");
        return false;
    }

    public function addCommentToClickUpTask(TicketComment $comment)
    {
        if ($comment->ticket->clickup_task_id && !$comment->clickup_comment_id) {
            $commentText = "{$comment->author->name} commented: \n{$comment->message}";
            if ($comment->file_path) {
                $commentText .= "\nAttachment: " . asset('storage/' . $comment->file_path);
            }

            $response = $this->clickUpApi->addCommentToTask($comment->ticket->clickup_task_id, $commentText);

            if ($response && isset($response['id'])) {
                $comment->clickup_comment_id = $response['id'];
                $comment->save();
                Log::info("Comment #{$comment->id} added to ClickUp Task ID: {$comment->ticket->clickup_task_id}");
                return true;
            }
            Log::error("Failed to add comment to ClickUp task for Comment #{$comment->id}");
        }
        return false;
    }

    /**
     * Memproses payload dari webhook ClickUp untuk disinkronkan ke database lokal.
     * Versi ini sudah diperkuat untuk menangani berbagai jenis payload.
     *
     * @param array $clickUpPayload
     * @return void
     */
    public function getStatusIdByName(string $statusName): ?int
    {
        // Mencari nama status (case-insensitive) di dalam mapping
        $localId = array_search(strtolower($statusName), array_map('strtolower', $this->clickUpStatusMapping));
        return $localId === false ? null : (int)$localId;
    }
    public function syncClickUpToLocal(array $clickUpPayload)
    {
        // PERBAIKAN: Cek apakah payload berisi 'event' dan 'task_id'.
        // Jika tidak, kemungkinan ini adalah "test payload" dari tombol Test, jadi kita abaikan.
        if (!isset($clickUpPayload['event']) || !isset($clickUpPayload['task_id'])) {
            Log::info('ClickUp Webhook: Menerima payload yang tidak memiliki "event" atau "task_id", kemungkinan "Test Payload". Diabaikan dengan aman.', $clickUpPayload);
            return; // Hentikan eksekusi
        }

        $eventType = $clickUpPayload['event'];
        $taskId = $clickUpPayload['task_id'];

        $ticket = Ticket::where('clickup_task_id', $taskId)->first();

        if (!$ticket) {
            Log::warning("ClickUp Webhook: Menerima event untuk Task ID yang tidak dikenal di sistem lokal: {$taskId}");
            return;
        }

        // Pastikan 'history_items' ada sebelum diakses untuk mencegah error
        if (!isset($clickUpPayload['history_items']) || !is_array($clickUpPayload['history_items']) || empty($clickUpPayload['history_items'])) {
            Log::info("ClickUp Webhook: Event '{$eventType}' untuk Task ID {$taskId} diterima tanpa 'history_items'. Diabaikan.", $clickUpPayload);
            return;
        }

        switch ($eventType) {
            case 'taskStatusUpdated':
        
                Log::info("--- Memulai Debugging taskStatusUpdated untuk Tiket #{$ticket->id} ---");

                $newStatusName = $clickUpPayload['history_items'][0]['after']['status'] ?? null;
                Log::info("1. Status yang diterima dari ClickUp: '{$newStatusName}'");

                $localStatusId = $newStatusName ? array_search(strtolower($newStatusName), array_map('strtolower', $this->clickUpStatusMapping)) : false;
                Log::info("2. Hasil pencarian ID Status lokal: " . ($localStatusId === false ? 'TIDAK DITEMUKAN' : $localStatusId));

                Log::info("3. ID Status tiket saat ini di database: {$ticket->status_id}");

                if ($localStatusId !== false && $ticket->status_id != $localStatusId) {
                    Log::info("4. KONDISI TERPENUHI. Menyimpan status baru...");
                    $ticket->status_id = $localStatusId;
                    $ticket->save();
                    $this->addSystemComment($ticket, "Status tiket diperbarui dari ClickUp menjadi: **{$newStatusName}**");
                    Log::info("5. SUKSES: Status tiket #{$ticket->id} berhasil diperbarui.");
                } else {
                    Log::info("4. KONDISI TIDAK TERPENUHI. Perubahan dilewati. Alasan: ID tidak ditemukan ATAU status sudah sama.");
                }
                Log::info("--- Selesai Debugging taskStatusUpdated ---");
                
                break;

            case 'taskPriorityUpdated':
                $taskDetails = $this->clickUpApi->getTask($taskId);
                if ($taskDetails && isset($taskDetails['priority']['id'])) {
                    $newPriorityLevel = (int)$taskDetails['priority']['id'];
                    $localPriorityId = array_search($newPriorityLevel, $this->clickUpPriorityMapping);
                    if ($localPriorityId && $ticket->priority_id != $localPriorityId) {
                        $ticket->priority_id = $localPriorityId;
                        $ticket->save();
                        $this->addSystemComment($ticket, "Prioritas tiket diperbarui dari ClickUp.");
                    }
                }
                break;

            case 'taskCommentPosted':
                $commentData = $clickUpPayload['history_items'][0]['comment'] ?? null;
                if ($commentData) {
                    $clickupCommentId = $commentData['id'];
                    if (!TicketComment::where('clickup_comment_id', $clickupCommentId)->exists()) {
                        $authorName = $commentData['user']['username'] ?? 'User ClickUp';
                        $ticket->comments()->create([
                            'account_id' => $this->getSystemAccountId(),
                            'message' => "**Komentar dari {$authorName} (via ClickUp):**\n\n" . $commentData['text_content'],
                            'clickup_comment_id' => $clickupCommentId,
                        ]);
                    }
                }
                break;

            default:
                Log::info("ClickUp Webhook: Menerima event yang tidak ditangani: {$eventType} untuk Task ID: {$taskId}");
                break;
        }
    }

    private function addSystemComment(Ticket $ticket, string $message): void
    {
        $ticket->comments()->create([
            'account_id' => $this->getSystemAccountId(),
            'message' => $message
        ]);
    }

    private function getSystemAccountId(): int
    {
        return 1;
    }
}
