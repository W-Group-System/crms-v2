<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesApprovers extends Model
{
    protected $table = 'salesapprovers';
    protected $primaryKey = 'Id';

    CONST CREATED_AT = 'CreatedDate';
    CONST UPDATED_AT = 'ModifiedDate';
}
