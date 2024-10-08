<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpeFiles extends Model
{
    use SoftDeletes;

    protected $table = "spefiles";
    protected $fillable = [
        'SpeId', 'Name', 'Path'
    ];
}
