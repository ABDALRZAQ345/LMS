<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Achievement extends Model
{
    protected $table = 'achievements';

    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $hidden = [
        'updated_at', 'id', 'pivot',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'achievement_user');

    }
}
