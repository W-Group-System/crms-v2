<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpeDisposition extends Model
{

    use SoftDeletes;
    
    protected $table = "spedisposition";
    protected $fillable = [
        'SpeId', 'Disposition'
    ];
}
