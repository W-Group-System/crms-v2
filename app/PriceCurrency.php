<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PriceCurrency extends Model
{
    use SoftDeletes;
    protected $table = "productmaterialpricecurrencies";
    protected $fillable = [
        'Name', 'Description'
    ];
}
