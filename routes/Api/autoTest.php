<?php
//
//
//use Illuminate\Support\Facades\Route;
//
//    Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {
//
//
//        Route::get('/latest-code', function () {
//            $file = storage_path('logs/verification_code.log');
//            if (!file_exists($file)) return response()->json(['error' => 'file not found'], 404);
//
//            $content = file_get_contents($file);
//            $code = substr(trim($content), -6);
//
//            return response()->json(['code' => $code]);
//        });
//
//
//    });
//
