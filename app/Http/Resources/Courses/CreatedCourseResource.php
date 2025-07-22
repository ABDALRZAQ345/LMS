<?php

namespace App\Http\Resources\Courses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreatedCourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {


        $data = [
            'id' => $this->id,
            'title_of_course' => $this->title,
            'description_of_course' => $this->description,
            'rate' => $this->rate,
            'image_of_course' => getPhoto($this->image),
            'number_of_video' => $this->videos_count,
            'number_of_test' => $this->tests_count,
            'price' => $this->price,
            'teacher_id' => $this->teacher->id,
            'teacher_name' => $this->teacher->name,
            'teacher_image' => getPhoto($this->teacher->image),
            'level' => $this->level,
            'course_duration' => $this->formatDuration($this->videos_sum_duration),
        ];
        if ($request->is('api/courses/*')) {
            $data['learning_paths'] = $this->whenLoaded('learningPaths', function () {
                return $this->learningPaths->map(function ($path) {
                    return [
                        'id' => $path->id,
                        'name' => $path->title,
                        'image' => getPhoto($path->image)
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

