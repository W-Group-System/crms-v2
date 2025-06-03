<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SatisfactionFile extends Model
{
    use SoftDeletes;
    protected $table = "satisfaction_files";
    protected $fillable = ['CsId', 'Path'];
}
