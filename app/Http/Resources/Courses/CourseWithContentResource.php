<?php

namespace App\Http\Resources\Courses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseWithContentResource extends JsonResource
{
    protected $content;

    public function __construct($resource, $content)
    {
        parent::__construct($resource);
        $this->content = $content;
    }

    public function toArray(Request $request): array
    {
        $imageOfTeacher = $this->teacher->image
            ? (str_starts_with($this->teacher->image, 'https://via.placeholder.com')
                ? $this->teacher->image
                : config('app.url').$this->teacher->image)
            : null;




        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'rate' => $this->rate,
            'image' => $this->image,
            'price' => $this->price == 0 ? 'free' : $this->price,
            'level'=> $this->level,
            'status' => $this->pivot->status ?? null,
            'student_paid' => $this->pivot->paid ?? null,
            'teacher_id' => $this->teacher->id,
            'teacher_name' => $this->teacher->name,
            'teacher_image' => $imageOfTeacher,
            'learning_paths' => $this->learningPaths->map(function ($path) {
                return [
                    'id' => $path->id,
                    'title' => $path->title,
                    'image' => $path->image
                        ? (str_starts_with($path->image, 'https://via.placeholder.com')
                            ? $path->image
                            : config('app.url') . $path->image)
                        : null,
                ];
            }),


            'content' => $this->content->map(function ($item) {
                $contentItem = [
                    'id' => $item->id,
                    'title' => $item->title,
                    'type' => $item instanceof \App\Models\Video ? 'video' : 'test',
                    'order' => $item->order,
                ];

                if ($item instanceof \App\Models\Video) {
                    $contentItem['is_free'] = (bool) $item->free;
                }

                if ($item instanceof \App\Models\Test) {
                    $contentItem['is_final'] = (bool) $item->is_final;
                }

                return $contentItem;
            })->values(),
        ];
    }
}
