<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CcEmail extends Model
{
    use SoftDeletes;
    
    protected $table = 'ccemail';
}
