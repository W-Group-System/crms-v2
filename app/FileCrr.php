<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileCrr extends Model
{
    use SoftDeletes;
    protected $table = "crrfiles";
    protected $primaryKey = 'Id';

    CONST CREATED_AT  = "CreatedDate";
    CONST UPDATED_AT  = "ModifiedDate";
}
