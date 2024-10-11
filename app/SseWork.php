<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SseWork extends Model
{
    use SoftDeletes;

    protected $table = "ssework";
    protected $fillable = [
        'SseId', 'Work'
    ];
}
