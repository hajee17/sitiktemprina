<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClickUpApiService
{
    protected $baseUrl;
    protected $apiKey;
    protected $teamId;

    public function __construct()
    {
        $this->baseUrl = 'https://api.clickup.com/api/v2';
        $this->apiKey = env('CLICKUP_API_KEY');
        $this->teamId = env('CLICKUP_TEAM_ID');
    }

    protected function client()
    {
        return Http::withHeaders([
            'Authorization' => $this->apiKey,
            'Content-Type' => 'application/json',
        ]);
    }

    public function createTask(string $listId, array $data)
    {
        try {
            $response = $this->client()->post("{$this->baseUrl}/list/{$listId}/task", $data);
            $response->throw();
            Log::info("ClickUp API: Task created successfully.", ['listId' => $listId, 'response' => $response->json()]);
            return $response->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error("ClickUp API Error (createTask): " . $e->getMessage(), [
                'listId' => $listId,
                'data' => $data,
                'response_body' => $e->response ? $e->response->body() : 'N/A'
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error("ClickUp API General Error (createTask): " . $e->getMessage(), ['listId' => $listId, 'data' => $data]);
            return null;
        }
    }

    public function updateTask(string $taskId, array $data)
    {
        try {
            $response = $this->client()->put("{$this->baseUrl}/task/{$taskId}", $data);
            $response->throw();
            Log::info("ClickUp API: Task updated successfully.", ['taskId' => $taskId, 'response' => $response->json()]);
            return $response->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error("ClickUp API Error (updateTask): " . $e->getMessage(), [
                'taskId' => $taskId,
                'data' => $data,
                'response_body' => $e->response ? $e->response->body() : 'N/A'
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error("ClickUp API General Error (updateTask): " . $e->getMessage(), ['taskId' => $taskId, 'data' => $data]);
            return null;
        }
    }

    public function addCommentToTask(string $taskId, string $commentText)
    {
        try {
            $response = $this->client()->post("{$this->baseUrl}/task/{$taskId}/comment", [
                'comment_text' => $commentText,
            ]);
            $response->throw();
            Log::info("ClickUp API: Comment added successfully.", ['taskId' => $taskId, 'response' => $response->json()]);
            return $response->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error("ClickUp API Error (addCommentToTask): " . $e->getMessage(), [
                'taskId' => $taskId,
                'commentText' => $commentText,
                'response_body' => $e->response ? $e->response->body() : 'N/A'
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error("ClickUp API General Error (addCommentToTask): " . $e->getMessage(), ['taskId' => $taskId, 'commentText' => $commentText]);
            return null;
        }
    }

    /**
     * Mengambil detail tugas dari ClickUp.
     * @param string $taskId ID tugas ClickUp.
     * @return array|null Respons JSON atau null jika terjadi error.
     */
    public function getTask(string $taskId)
    {
        try {
            $response = $this->client()->get("{$this->baseUrl}/task/{$taskId}");
            $response->throw();
            return $response->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error("ClickUp API Error (getTask): " . $e->getMessage(), ['taskId' => $taskId]);
            return null;
        } catch (\Exception $e) {
            Log::error("ClickUp API General Error (getTask): " . $e->getMessage(), ['taskId' => $taskId]);
            return null;
        }
    }
    public function getUpdatedTasks(string $listId, int $timestamp)
    {
        try {
            $response = $this->client()->get("{$this->baseUrl}/list/{$listId}/task", [
                'date_updated_gt' => $timestamp
            ]);
            $response->throw();
            return $response->json()['tasks'] ?? [];
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error("ClickUp API Error (getUpdatedTasks): " . $e->getMessage(), [
                'listId' => $listId,
                'response_body' => $e->response ? $e->response->body() : 'N/A'
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error("ClickUp API General Error (getUpdatedTasks): " . $e->getMessage(), ['listId' => $listId]);
            return null;
        }
    }
}