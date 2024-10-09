<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SseFiles extends Model
{
    use SoftDeletes;

    protected $table = "ssefiles";
    protected $fillable = [
        'SseId', 'Name', 'Path'
    ];
}
