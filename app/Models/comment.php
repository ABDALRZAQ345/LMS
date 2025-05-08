<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class comment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }



    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }
}
