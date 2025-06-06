<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    /** @use HasFactory<\Database\Factories\ProblemFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected $hidden = [
        'test_input',
        'expected_output',
        'created_at',
        'updated_at',
    ];

    public function submission()
    {
        return $this->hasMany(Submission::class);
    }
}
