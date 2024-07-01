<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use SoftDeletes;
    
    protected $table = "clientcountries";
    protected $fillable = [
        'Name', 'Description'
    ];
}
