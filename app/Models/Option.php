<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];
    protected $hidden=['created_at','updated_at','question_id','is_correct'];
    public function question(): BelongsTo
    {
        return $this->BelongsTo(Question::class);
    }
}
