<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVideoProgress extends Model
{
    protected $table = 'user_video_progress';
    protected $guarded = [];

    protected $casts = [
        'is_completed' => 'boolean',
        'last_watched_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
    public function course(){
        return $this->belongsTo(Course::class);
    }

}
