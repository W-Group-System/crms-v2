<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CsFiles extends Model
{
    protected $table = "csfiles";
    protected $fillable = [
        'CsId', 'Path'
    ];
}
