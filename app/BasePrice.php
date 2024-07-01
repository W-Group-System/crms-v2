<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class BasePrice extends Model
{
    use SoftDeletes;
    protected $table = "productmaterialbaseprices";

}
