<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    /** @use HasFactory<\Database\Factories\SubmissionFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function problem()
    {
        return $this->belongsTo(Problem::class);
    }
}
