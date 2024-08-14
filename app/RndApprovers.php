<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RndApprovers extends Model
{
    protected $table = 'rndapprovers';
    protected $primaryKey = 'Id';
    
    CONST CREATED_AT = 'CreatedDate';
    CONST UPDATED_AT = 'ModifiedDate';
}
