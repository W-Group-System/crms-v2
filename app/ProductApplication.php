<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ProductApplication extends Model
{
    use SoftDeletes;
    protected $table = "productapplications";
    protected $fillable = [
        'Name', 'Description'
    ];
}
