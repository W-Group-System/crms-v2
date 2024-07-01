<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class BusinessType extends Model
{
    use SoftDeletes;
    protected $table = "clientbusinesstypes";
    protected $fillable = [
        'Name', 'Description'
    ];
}
