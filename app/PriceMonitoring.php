<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PriceMonitoring extends Model
{
    use SoftDeletes;
    protected $table = "pricerequestforms";
}
