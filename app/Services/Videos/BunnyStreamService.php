<?php

namespace App\Services\Videos;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class BunnyStreamService
{
    protected $libraryId;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->libraryId = config('services.bunny.library_id');
        $this->apiKey = config('services.bunny.api_key');
        $this->baseUrl = config('services.bunny.api_url');
    }

    public function uploadVideo(UploadedFile $file, string $title): string
    {
        \Log::debug('Current Bunny API key:', [config('services.bunny.api_key')]);
        // إنشاء فيديو
        $create = Http::withToken($this->apiKey)->post("{$this->baseUrl}/library/{$this->libraryId}/videos", [
            'title' => $title,
        ]);
        \Log::debug('Bunny response status:', [$create->status()]);
        \Log::debug('Bunny full response body:', [$create->body()]);

        if (!$create->successful()) {
            throw new \Exception('Failed to create video');
        }

        $videoId = $create->json()['guid'];

        // رفع الملف
        $upload = Http::withToken($this->apiKey)
            ->withHeaders(['Content-Type' => 'application/octet-stream'])
            ->put("{$this->baseUrl}/library/{$this->libraryId}/videos/{$videoId}", fopen($file->getPathname(), 'r'));

        if (!$upload->successful()) {
            throw new \Exception('Failed to upload video file');
        }

        return $videoId;
    }

    /**
     * حذف فيديو
     */
    public function deleteVideo(string $videoId): void
    {
        $response = Http::withToken($this->apiKey)
            ->delete("{$this->baseUrl}/library/{$this->libraryId}/videos/{$videoId}");

        if (!$response->successful()) {
            throw new \Exception('Failed to delete video from Bunny Stream');
        }
    }


    /**
     * تعديل عنوان فيديو
     */
    public function updateVideoTitle(string $videoId, string $newTitle): void
    {
        $response = Http::withToken($this->apiKey)->post("{$this->baseUrl}/library/{$this->libraryId}/videos/{$videoId}", [
            'title' => $newTitle,
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to update video title');
        }
    }

    /**
     * استعلام عن فيديو
     */
    public function getVideoInfo(string $videoId): array
    {
        $response = Http::withToken($this->apiKey)
            ->get("{$this->baseUrl}/library/{$this->libraryId}/videos/{$videoId}");

        if (!$response->successful()) {
            throw new \Exception("Failed to fetch Bunny video info");
        }

        return $response->json();
    }

}

