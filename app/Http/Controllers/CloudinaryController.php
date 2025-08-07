<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CloudinaryController extends Controller
{
    public function getSignature()
    {
        $timestamp = time();
        $params = [
            'timestamp' => $timestamp,
            'folder' => 'videos',
        ];

        ksort($params);

        $signatureString = urldecode(http_build_query($params)) . env('CLOUDINARY_API_SECRET');
        $signature = sha1($signatureString);

        return response()->json([
            'signature' => $signature,
            'timestamp' => $timestamp,
            'api_key' => env('CLOUDINARY_API_KEY'),
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
            'folder' => 'videos',
        ]);
    }
}
