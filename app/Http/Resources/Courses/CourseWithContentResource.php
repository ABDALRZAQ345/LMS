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
                : config('app.url').'/storage/'.$this->teacher->image)
            : null;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'rate' => $this->rate,
            'image' => $this->image,
            'price' => $this->price == 0 ? 'free' : $this->price,
            'teacher_id' => $this->teacher->id,
            'teacher_name' => $this->teacher->name,
            'teacher_image' => $imageOfTeacher,

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
