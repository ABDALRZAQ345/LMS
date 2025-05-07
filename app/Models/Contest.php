<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function question(){
        return $this->morphOne(Question::class, 'questionable');
    }

    public function questions(){
        return $this->morphmany(Question::class, 'questionable');
    }
    public function students(){
        return $this->hasMany(Student::class);
    }
}
