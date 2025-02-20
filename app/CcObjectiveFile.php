<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CcObjectiveFile extends Model
{
    use SoftDeletes;
    
    protected $table = 'ccobjectivefiles';
    protected $fillable = [
        'CcId', 'Path'
    ];
}
