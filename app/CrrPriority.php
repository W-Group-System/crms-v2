<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CrrPriority extends Model
{
    use SoftDeletes;
    protected $table = "crrpriorities";
    protected $fillable = [
        'Name', 'Description', 'Days'
    ];
}
