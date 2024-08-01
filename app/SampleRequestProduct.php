<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Audit;


class SampleRequestProduct extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = "samplerequestproducts";


    protected $fillable = [
        'id',
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
