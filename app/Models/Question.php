<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Question extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function questionable(): MorphTo
    {
        return $this->morphTo();
    }
    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }
    public function correctOption(){
        return $this->options()->where('is_correct',1)->first();
    }
}
