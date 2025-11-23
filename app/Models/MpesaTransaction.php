<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MpesaTransaction extends Model
{
    protected $fillable = ['external_id','type','payload'];
    protected $casts = ['payload' => 'array'];
}


