<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $hidden=['user_id','id','updated_at'];
    /** @use HasFactory<\Database\Factories\CertificateFactory> */
    use HasFactory;
}
