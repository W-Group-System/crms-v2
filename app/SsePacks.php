<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SsePacks extends Model
{
    use SoftDeletes;

    protected $table = "ssepacks";
    protected $fillable = [
        'SseId', 'LotNumber', 'QtyRepresented'
    ];
}
