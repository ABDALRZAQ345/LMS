<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LearningPath extends Model
{
    use HasFactory;

    protected $table = 'learning_paths';

    protected $guarded = [
        'id',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_learning_path')
            ->orderByPivot('order');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'learning_path_user')
            ->withPivot('paid', 'status')
            ->withTimestamps();
    }
}
