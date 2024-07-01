<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ConcernDepartment extends Model
{
    use SoftDeletes;
    protected $table = "customerserviceconcerneddepartm";
    protected $fillable = [
        'Name', 'Description'
    ];
}
