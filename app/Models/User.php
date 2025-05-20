<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'email', 'image', 'bio', 'role', 'password', 'fcm_token', 'gitHub_account', 'points', 'last_online', 'email_verified', 'github_id', 'github_token',
    ];

    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password', 'fcm_token', 'email_verified', 'remember_token', 'updated_at', 'google_id', 'pivot', 'github_id', 'github_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [

            'password' => 'hashed',
            'created_at' => 'date',
        ];
    }

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function achievements(): belongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'achievement_user');

    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function reviews(): HasMany
    {
        return $this->HasMany(Review::class);
    }

    public function allCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_user')
            ->withPivot('paid', 'status');

    }

    public function paidCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_user')
            ->wherePivot('paid', true)
            ->withPivot('paid', 'status');
    }

    public function unPaidCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_user')
            ->wherePivot('paid', true)
            ->withPivot('paid', 'status');
    }

    public function finishedCourses()
    {
        return $this->belongsToMany(Course::class, 'course_user')
            ->wherePivot('status', 'finished')
            ->withPivot('paid', 'status');
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'course_user')
            ->wherePivot('status', 'enrolled')
            ->withPivot('paid', 'status');
    }

    public function watchLaterCourses()
    {
        return $this->belongsToMany(Course::class, 'course_user')
            ->wherePivot('status', 'watch_later')
            ->withPivot('paid', 'status');
    }

    public function tests(): BelongsToMany
    {
        return $this->belongsToMany(Test::class, 'test_user')
            ->withPivot('start_time', 'end_time', 'correct_answers');
    }

    public function contests(): BelongsToMany
    {
        return $this->belongsToMany(Contest::class, 'contest_user')
            ->withPivot('end_time', 'correct_answers', 'gained_points', 'rank');
    }

    public function createdContests(): HasMany
    {
        return $this->hasMany(Contest::class)
            ->withCount('students');
    }

    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'friends',
            'user_id',
            'friend_id'
        );

    }

    public function createdCourses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function verifiedCourses(): HasMany
    {
        return $this->hasMany(Course::class)->where('verified', true);
    }

    public function unVerifiedCourses(): HasMany
    {
        return $this->hasMany(Course::class)->where('verified', false);
    }

    public function paidCreatedCourses(): HasMany
    {
        return $this->hasMany(Course::class)->where('paid', true);
    }

    public function unPaidCreatedCourses(): HasMany
    {
        return $this->hasMany(Course::class)->where('paid', false);
    }

    public function CreatedLearningPaths(): HasMany
    {
        return $this->hasMany(LearningPath::class);
    }

    public function learningPaths(): BelongsToMany
    {
        return $this->belongsToMany(LearningPath::class, 'learning_path_user');

    }

    public function verifiedLearningPaths(): HasMany
    {
        return $this->hasMany(LearningPath::class)->where('verified', true);
    }

    public function unVerifiedLearningPaths(): HasMany
    {
        return $this->hasMany(LearningPath::class)->where('verified', false);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function streaks(): HasMany
    {
        return $this->hasMany(Streak::class)
            ->orderBy('date');
    }

    public function LongestStreak()
    {
        return $this->streaks()->max('current_streak');
    }

    public function CurrentStreak()
    {
        $streak = $this->streaks()->where('date', now()->toDateString())->first();

        return $streak ? $streak->current_streak : 0;
    }

    public function allLearningPaths(): BelongsToMany
    {
        return $this->belongsToMany(LearningPath::class, 'learning_path_user')
            ->withPivot('paid', 'status');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
