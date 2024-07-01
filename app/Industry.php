<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    use SoftDeletes;
    protected $table = "clientindustries";
    protected $fillable = [
        'Name', 'Description'
    ];
}
