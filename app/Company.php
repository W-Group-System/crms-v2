<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use SoftDeletes;
    protected $table = "companies";
    protected $fillable = [
        'name', 'description'
    ];
}
