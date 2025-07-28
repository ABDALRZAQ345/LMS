<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'common nigga',
    ]);
})->middleware(['locale', 'throttle:api', 'xss']);


Route::get('/testCookie', function () {
    $lang = request()->cookie('lang', 'en'); // القيمة الافتراضية en
    $message = $lang === 'ar' ? 'مرحبًا' : 'Hello';

    return <<<HTML
        <html>
            <body>
                <h1>{$message}</h1>
                <a href="/lang/ar">العربية</a> |
                <a href="/lang/en">English</a>
            </body>
        </html>
    HTML;
});

Route::get('/lang/{lang}', function ($lang) {
    return redirect('/')
        ->withCookie(cookie('lang', $lang, 60 * 24 * 30)); // تخزين الكوكي لمدة شهر
});
