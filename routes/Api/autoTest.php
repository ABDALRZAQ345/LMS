<?php
//
//use App\Http\Controllers\Auth\AuthController;
//use App\Http\Controllers\Auth\FcmTokenController;
//use App\Http\Controllers\Auth\GithubController;
//use App\Http\Controllers\Auth\GoogleAuthController;
//use App\Http\Controllers\Auth\PasswordController;
//use App\Http\Controllers\Auth\VerificationCodeController;
//use Illuminate\Support\Facades\Route;
//
//Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {
////todo need to be deleted or used just in local not in production
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
//
//});
