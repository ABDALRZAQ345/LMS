<?php

use Illuminate\Support\Facades\Storage;

if (! function_exists('NewPublicPhoto')) {
    function NewPublicPhoto($photo, $folder = 'images'): string
    {
        // رفع الملف على S3
        $path = $photo->store($folder, 's3');

        // إرجاع الرابط المباشر للملف
        return Storage::disk('s3')->url($path);
    }
}
function  getPhoto($image)
{
    if($image=='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTkoyUQaux4PEUmEPGc7PodeN8XbgC4aOBsug&s')
        return $image;
     return $image;
}
if (! function_exists('DeletePublicPhoto')) {
    function DeletePublicPhoto($path): void
    {
        $oldPhotoPath = str_replace('storage/', 'public/', $path);
        Storage::delete($oldPhotoPath);
    }

}

if (! function_exists('getPercentage')) {
    function getPercentage($sub, $total,bool $asInt=false)
    {
        if(!$asInt)
        return ($total > 0 ? ($sub / $total) * 100 : 0).'%';

        else return ($total > 0 ? ($sub / $total) * 100 : 0);
    }

}
if (! function_exists('NewPublicVideo')) {
    function NewPublicVideo($video, $folder = 'videos'): string
    {
        $videoPath = $video->store($folder, 'public');
        $videoPath ='/storage/'.$videoPath;

        return $videoPath;
    }
}
function  getVideo($video)
{
    return $video
        ? (str_starts_with($video, 'https://')
            ? $video
            : config('app.url') . $video)
        : null;
}

    function getMeta($data): array
    {
        return  [
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'per_page' => $data->perPage(),
            'total' => $data->total(),
        ];
    }


