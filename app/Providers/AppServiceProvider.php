<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // \URL::forceScheme('https');
        $this->observers();
        $this->rateLimiters();
        $this->GatesAndPolicies();
        $this->routes();
        $this->productionConfigurations();
        $this->PassWordConfigurations();
    }

    public function GatesAndPolicies() {}

    private function observers(): void {}

    private function rateLimiters(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });
        RateLimiter::for('send_confirmation_code', function (Request $request) {
            return [
                Limit::perDay(30)->by($request->user()?->id ?: $request->ip()),
            ];
        });
        RateLimiter::for('check_verification_code', function (Request $request) {
            return Limit::perDay(20)->by($request->user()?->id ?: $request->ip());
        });
        RateLimiter::for('register', function (Request $request) {
            return [
                Limit::perMinutes(30, 10)->by($request->user()?->id ?: $request->ip()),
                Limit::perDay(40)->by($request->user()?->id ?: $request->ip()),
            ];
        });
        RateLimiter::for('change_password', function (Request $request) {
            return Limit::perDay(10)->by($request->user()?->id ?: $request->ip());
        });
        RateLimiter::for('web', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });
        RateLimiter::for('rare', function (Request $request) {
            return Limit::perDay(2)->by($request->user()?->id ?: $request->ip());
        });
    }

    private function routes(): void
    {
        $apiRouteFiles = [
            'auth.php',
            'user.php',
            'googleAuth.php',
            'course.php',
            'learningPath.php',


        ];
        foreach ($apiRouteFiles as $routeFile) {
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path("routes/Api/{$routeFile}"));
        }
    }

    private function productionConfigurations(): void
    {
        Model::shouldBeStrict(! app()->environment('production'));
        Model::preventLazyLoading(! app()->environment('production'));

    }

    private function PassWordConfigurations(): void
    {
        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->max(75);
        });
    }
}
