<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Streak extends Model
{
    /** @use HasFactory<\Database\Factories\StreakFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at', 'current_streak', 'user_id', 'id'];
}
