<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $hidden = ['user_id', 'id', 'updated_at'];
    protected $guarded = ['id'];
    /** @use HasFactory<\Database\Factories\CertificateFactory> */
    use HasFactory;

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
