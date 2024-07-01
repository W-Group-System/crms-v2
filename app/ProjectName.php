<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ProjectName extends Model
{
    use SoftDeletes;
    protected $table = "rpeprojectnames";
    protected $fillable = [
        'Name', 'Description'
    ];
}
