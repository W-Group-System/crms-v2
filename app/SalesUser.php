<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SalesUser extends Model
{
    // use SoftDeletes;
    protected $table = "salesusers";
   
}
