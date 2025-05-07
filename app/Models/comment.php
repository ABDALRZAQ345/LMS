<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function student(){
        return $this->belongsTo(Student::class);
    }
    public function comment(){
        return $this->belongsTo(Comment::class);
    }

}
