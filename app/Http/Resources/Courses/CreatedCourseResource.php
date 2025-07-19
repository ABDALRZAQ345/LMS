<?php

namespace App\Http\Resources\Courses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreatedCourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $imageUrl = $this->image
            ? (str_starts_with($this->image, 'https://via.placeholder.com')
                ? $this->image
                : config('app.url').'/storage/'.$this->image)
            : null;

        $teacherImageUrl = $this->teacher->image
            ? (str_starts_with($this->teacher->image, 'https://via.placeholder.com')
                ? $this->teacher->image
                : config('app.url').'/storage/'.$this->teacher->image)
            : null;

        $data = [
            'id' => $this->id,
            'title_of_course' => $this->title,
            'description_of_course' => $this->description,
            'rate' => $this->rate,
            'image_of_course' => $imageUrl,
            'number_of_video' => $this->videos_count,
            'number_of_test' => $this->tests_count,
            'price' => $this->price,
            'teacher_id' => $this->teacher->id,
            'teacher_name' => $this->teacher->name,
            'teacher_image' => $teacherImageUrl,
            'level' => $this->level,
            'course_duration' => $this->formatDuration($this->videos_sum_duration),
        ];
        if ($request->is('api/courses/*')) {
            $data['learning_paths'] = $this->whenLoaded('learningPaths', function () {
                return $this->learningPaths->map(function ($path) {
                    return [
                        'id' => $path->id,
                        'name' => $path->title,
                        'image' => $path->image
                            ? (str_starts_with($path->image, 'https://via.placeholder.com')
                                ? $path->image
                                : config('app.url').'/storage/'.$path->image)
                            : null,
                    ];
                });
            });
        }

        return $data;
    } private function formatDuration($minutes)
{
    $hours = floor($minutes / 60);
    $remainingMinutes = $minutes % 60;

    return sprintf('%d:%02d h', $hours, $remainingMinutes);
}

}

