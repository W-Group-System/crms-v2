<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CcVerificationFile extends Model
{
    use SoftDeletes;
    
    protected $table = 'ccverificationfiles';
    protected $fillable = [
        'Path'
    ];
}
