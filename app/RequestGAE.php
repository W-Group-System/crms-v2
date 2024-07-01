<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class RequestGAE extends Model
{
    use SoftDeletes;
    protected $table = "pricerequestgaes";
    protected $fillable = [
        'ExpenseName', 'Cost'
    ];
}
