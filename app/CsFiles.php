<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CsFiles extends Model
{
    use SoftDeletes;
    protected $table = "csfiles";
    protected $fillable = ['CsId', 'Path']; // Enable mass assignment
}