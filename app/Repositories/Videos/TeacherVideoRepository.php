<?php

namespace App\Repositories\Videos;

use App\Models\Course;
use App\Models\Test;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

class TeacherVideoRepository
{

    public function showOneVideoInCourse($course, $video){
        return Video::where('course_id', $course->id)
            ->where('id', $video->id)
            ->first();
    }
    public function createUrl($validate){
        $course = Course::findOrFail($validate['course_id']);
        $validate['order']= $course->content()->count() + 1;
        $finalTest=Test::where('course_id',$course->id)->where('is_final',1)->first();

        if($finalTest){
            $validate['order']--;
            $finalTest->increment('order');
        }

        $video = Video::create($validate);
        return $video;
    }

    public function deleteVideo($video){
        if ($this->isLocalStorageUrl($video->url)) {
            $relative = $this->relativeFromPublicStorageUrl($video->url);

            if ($relative && Storage::disk('public')->exists($relative)) {
                Storage::disk('public')->delete($relative);
            }
        }
        $video->delete();
    }

    public function storageVideo($data){
        $course = Course::findOrFail($data['course_id']);

        $folder = 'videos/'.Str::slug($course->title);
        $publicUrl = NewPublicVideo($data['uploaded_file'], $folder);

        $relative = Str::after($publicUrl, '/storage/');
        $absolutePath = storage_path('app/public/'.$relative);

        $seconds = $this->extractDurationSeconds($absolutePath);
        $minutes = (int) round($seconds / 60);

        $order = $course->content()->count() + 1;
        $finalTest=Test::where('course_id',$course->id)->where('is_final',1)->first();

        if($finalTest){
            $order--;
            $finalTest->increment('order');
        }
        $payload = [
            'title'       => $data['title'],
            'description' => $data['description'],
            'url'         => $publicUrl,
            'free'        => (bool) $data['free'],
            'duration'    => $minutes,
            'order'       => $order,
            'course_id'   => $course->id,
        ];


        return Video::create($payload);
    }

    public function updateStorageVideo($video,$data){
        $hasNewFile = isset($data['uploaded_file']) && $data['uploaded_file'] instanceof UploadedFile;

        $payload = [];

        if (isset($data['title']))       $payload['title'] = $data['title'];
        if (isset($data['description'])) $payload['description'] = $data['description'];
        if (isset($data['free']))        $payload['free'] = (bool) $data['free'];

        $oldPublicUrl = $video->url;
        $newPublicUrl = null;

        if ($hasNewFile) {
            $course = Course::findOrFail($video->course_id);

            $folder = 'videos/'.Str::slug($course->title);
            $publicUrl = NewPublicVideo($data['uploaded_file'], $folder);

            $relativePath = Str::after($publicUrl, '/storage/');

            $absolutePath = storage_path('app/public/'.$relativePath);
            $seconds = $this->extractDurationSeconds($absolutePath);
            $minutes = (int) round($seconds / 60);

            $payload['url'] = $publicUrl;
            $payload['duration'] = $minutes;
        }

        $updated = $video->update($payload);

        if (!$updated && $hasNewFile && $relativePath) {
            Storage::disk('public')->delete($relativePath);
        }

        if ($updated && $hasNewFile && $oldPublicUrl) {
            $this->deleteOldVideo($oldPublicUrl);
        }

        return $video->refresh();
    }
    public function extractDurationSeconds(string $absolutePath): int
    {
        try {
            $getID3 = new \getID3;
            $info = $getID3->analyze($absolutePath);
            return isset($info['playtime_seconds'])
                ? (int) round($info['playtime_seconds'])
                : 0;
        } catch (\Throwable $e) {
            \Log::warning('Failed to extract duration: '.$e->getMessage());
            return 0;
        }
    }

    public function deleteOldVideo($publicUrlOrStoragePath): void
    {
        $storagePath = Str::startsWith($publicUrlOrStoragePath, '/storage/')
            ? ltrim(Str::after($publicUrlOrStoragePath, '/storage/'), '/')
            : ltrim($publicUrlOrStoragePath, '/');

        if (Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->delete($storagePath);
        } else {
            \Log::warning("Video not found for deletion: " . $storagePath);
        }
    }

    private function isLocalStorageUrl(?string $url): bool
    {
        if (!$url) {
            return false;
        }

        return Str::contains($url, '/storage/');
    }

    private function relativeFromPublicStorageUrl(string $url): ?string
    {
        if (!Str::contains($url, '/storage/')) {
            return null;
        }

        $relative = ltrim(Str::after($url, '/storage/'), '/');

        return $relative !== '' ? $relative : null;
    }

}
