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
        'IsDeleted',
        'ModifiedDate',
        'CreatedDate',
        'ProductIndex',
        'Disposition',
        'DispositionRejectionDescription',
    ];

    public function sampleRequest()
    {
        return $this->belongsTo(SampleRequest::class, 'SampleRequestId', 'Id');
    }

    public function productApplicationsId()
    {
        return $this->belongsTo(ProductApplication::class, 'ApplicationId', 'id');
    }

}
