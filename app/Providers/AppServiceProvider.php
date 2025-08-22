<?php

namespace App\Providers;

use App\Models\Contest;
use App\Models\Course;
use App\Models\LearningPath;
use App\Models\Like;
use App\Models\Project;
use App\Models\Review;
use App\Models\Submission;
use App\Models\User;
use App\Models\Video;
use App\Observers\LikeObserver;
use App\Observers\ReviewObserver;
use App\Observers\SubmissionObserver;
use App\Policies\ContestPolicy;
use App\Policies\CoursePolicy;
use App\Policies\LearningPathPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\UserPolicy;
use App\Policies\VideoPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use OpenApi\Annotations as OA;

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

    public function GatesAndPolicies(): void
    {
        Gate::policy(Contest::class, ContestPolicy::class);
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Course::class, CoursePolicy::class);
        Gate::policy(Video::class, VideoPolicy::class);
        Gate::policy(LearningPath::class, LearningPathPolicy::class);

    }

    private function observers(): void
    {
        Review::observe(ReviewObserver::class);
        Submission::observe(SubmissionObserver::class);
        Like::observe(LikeObserver::class);
    }

    private function rateLimiters(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return [
                Limit::perMinute(30)->by($request->user()?->id ?: $request->ip()),
                Limit::perDay(6000)->by($request->user()?->id ?: $request->ip())
            ];
        });
        RateLimiter::for('send_confirmation_code', function (Request $request) {
            return [
                Limit::perDay(25)->by($request->user()?->id ?: $request->ip()),
            ];
        });
        RateLimiter::for('check_verification_code', function (Request $request) {
            return Limit::perDay(25)->by($request->user()?->id ?: $request->ip());
        });
        RateLimiter::for('register', function (Request $request) {
            return [
                Limit::perMinutes(30, 15)->by($request->user()?->id ?: $request->ip()),
                Limit::perDay(30)->by($request->user()?->id ?: $request->ip()),
            ];
        });
        RateLimiter::for('change_password', function (Request $request) {
            return Limit::perDay(10)->by($request->user()?->id ?: $request->ip());
        });
        RateLimiter::for('web', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });
        RateLimiter::for('refresh_token', function (Request $request) {
            return Limit::perDay(100)->by($request->user()?->id ?: $request->ip());
        });
        RateLimiter::for('AiChat', function (Request $request) {
            return Limit::perDay(30)->by($request->user()?->id ?: $request->ip());
        });

    }

    private function routes(): void
    {
        $apiRouteFiles = [
            'auth.php',
            'user.php',
            'course.php',
            'learningPath.php',
            'friend.php',
            'review.php',
            'project.php',
            'admin.php',
            'teacher.php',
            'contest.php',
            'test.php',
            'video.php',
            'comment.php',
            'chat.php'
        ];
        if (config('app.env') != 'production') {
            $apiRouteFiles[] = 'autoTest.php';
        }
        foreach ($apiRouteFiles as $routeFile) {
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path("routes/Api/{$routeFile}"));
        }
    }

    private function productionConfigurations(): void
    {
        Model::shouldBeStrict(!app()->environment('production'));
        Model::preventLazyLoading(!app()->environment('production'));

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
