<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SampleRequestProduct extends Model
{
    use SoftDeletes;
    protected $table = "samplerequestproducts";

    protected $fillable = [
        'Id',
        'SampleRequestId',
        'ProductType', 
        'ApplicationId',
        'ProductCode',
        'ProductDescription',
        'NumberOfPackages',
        'Quantity',
        'UnitOfMeasureId',
        'Label',
        'RpeNumber',
        'CrrNumber',
        'Remarks',
    ];
}
