<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SseDisposition extends Model
{
    use SoftDeletes;

    protected $table = "ssedisposition";
    protected $fillable = [
        'SseId', 'LabDisposition', 'LabRemarks', 
    ];
}
