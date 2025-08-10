<?php

namespace App\Http\Controllers;




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

        $signatureString = urldecode(http_build_query($params)) . config('services.cloudinary.api_secret');
        $signature = sha1($signatureString);

        return response()->json([
            'signature' => $signature,
            'timestamp' => $timestamp,
            'api_key' => config('services.cloudinary.api_key'),
            'cloud_name' => config('services.cloudinary.cloud_name'),
            'folder' => 'videos',
        ]);
    }
}
